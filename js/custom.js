/**
 * Created by Mv on 12/29/2017.
 */


$(document).ready(function () {
    getMailchimpLists();
    checkEmailSubcription();
});

var user_email='vaghelamehul12345@gmail.com';

function checkEmailSubcription() {
    var complete_url = location.protocol + '//' + location.hostname + '/mailchimp.php';
    var url = './mailchimp.php';
    var methodName = 'checkEmailSubcription';
    var flag=1;
    var is_unsubscribe=0;
    $("#loader").show();
    $.ajax({
        type: "POST",
        url: url,
        data: {'Method': methodName, 'Email': user_email},
        success: function (data) {
            var resp = $.parseJSON(data);
            console.log('data:', resp);
            $("#user_status").empty();

            var html='<div class="lists-listing">' +
                '<div class="list-title"><h4>Your subcription Lists</h4></div>' +
                '<div class="list-body">';
            $.each(resp, function (key, val) {
                var repsone_data = $.parseJSON(val['response']);
                if (repsone_data['status'] == '404') {
                    console.log('val', repsone_data['status']);

                }
                if (repsone_data['status'] == 'subscribed') {
                    console.log('val', repsone_data['status']);
                    flag=0;
                    html+='<div class="row list-data-display">' +
                        '<div class="col-md-12">' +
                        '<h4>'+val['list_name']+'</h4>' +
                        '<a class="" onclick="unSubcribe(\''+val['list_id']+'\',\''+val['list_name']+'\',\''+user_email+'\')">Un-subcribe</a>' +
                        '</div>' +
                        '</div>';

                }
                if (repsone_data['status'] == 'unsubscribed') {
                    is_unsubscribe=1;
                    console.log('val', repsone_data['status']);
                    html+='<div class="row list-row">' +
                        '<div class="col-md-12">' +
                        '<input name="listname[]" type="checkbox" id="'+val['list_id']+'" value="'+val['list_name']+'"><span>'+val['list_name']+'</span>' +
                        '</div>' +
                        '</div>';
                }

            });
            if(flag){
                $("#mailchimp_lists").show();
            }else{
                if(is_unsubscribe){
                    html+='<button class="btn btn-primary" type="button" onclick="openSubcriptionModal()">Subcribe to Our Newslatter</button>';
                }
                html+='</div></div>';
                $("#mailchimp_lists").hide();
                $("#user_status").show();
                $("#user_status").html(html);
            }
            $("#loader").hide();


        }, error: function (e) {
            $("#loader").hide();
            notifyMe('Might have network issue !! Please Try Again Later..','error');
        }

    });
}

function unSubcribe(listId,Listname,UserEmail) {
    var complete_url = location.protocol + '//' + location.hostname + '/mailchimp.php';
    var url = './mailchimp.php';
    var methodName = 'unSubcribeUser';
    $("#loader").show();
    $.ajax({
        type: "POST",
        url: url,
        data: {'Method': methodName,'ListId':listId,'UserEmail':UserEmail},
        success: function (data) {
            var resp = $.parseJSON(data);
            console.log('list obj', resp);
            notifyMe('You have un-subcribed from the list: '+Listname+' successfully...','success');
            checkEmailSubcription();

            $("#loader").hide();

        }, error: function (e) {
            $("#loader").hide();
            notifyMe('Might have network issue !! Please Try Again Later..','error');
        }

    });
}

function openSubcriptionModal() {
    var atLeastOneIsChecked = $('input[name="listname[]"]:checked').length > 0;
    if(atLeastOneIsChecked){
        $("#user_email").val('');
        $("#fname").val('');
        $("#lname").val('');
        $("#myModal").modal('show');
    }else{
        notifyMe('Please Select lists','error');
    }

}
function subcribeUserMail() {
    var complete_url = location.protocol + '//' + location.hostname + '/mailchimp.php';
    var url = './mailchimp.php';
    var methodName = 'SubcribeUser';
    var $lists=$('input[name="listname[]"]:checked');
    var list_arrray=[];
    var list_names=[];
    var user_email=$("#user_email").val();
    var first_name=$("#fname").val();
    var last_name=$("#lname").val();
    $lists.each(function(){
        list_arrray.push(this.id);
        list_names.push(this.value);
    });
    if(user_email){
        $("#loader").show();
        $("#myModal").modal('hide');
        $.ajax({
            type: "POST",
            url: url,
            data: {'Method': methodName,'ListArray':list_arrray,'UserEmail':user_email,'FisrtName':first_name,'Last_name':last_name},
            success: function (data) {
                console.log('list obj', data);

                var resp = $.parseJSON(data);
                console.log('list obj', resp);
                notifyMe('Thank you for subscribing to this: '+list_names+' ','success');
                checkEmailSubcription();
                $("#loader").hide();



            }, error: function (e) {
                $("#loader").hide();
                notifyMe('Might have network issue !! Please Try Again Later..','error');
            }

        });
    }else{
        notifyMe('Please enter Email address..','error');
    }

}
function getMailchimpLists() {
    var complete_url = location.protocol + '//' + location.hostname + '/mailchimp.php';
    var url = './mailchimp.php';
    var methodName = 'getMailchimpLists';
    $.ajax({
        type: "POST",
        url: url,
        data: {'Method': methodName},
        success: function (data) {
            var resp = $.parseJSON(data);
            console.log('list obj', resp);
            $("#mailchimp_lists").empty();
            var html='<div class="lists-listing">' +
                '<div class="list-title"><h4>You are not subcribed to our mailchimp!! please subcribe..</h4></div>' +
                '<div class="list-body">';
            $.each(resp.lists, function (key, val) {
                html+='<div class="row list-row">' +
                    '<div class="col-md-12">' +
                    '<input name="listname[]" type="checkbox" id="'+val['id']+'" value="'+val['name']+'"><span> '+val['name']+'</span>' +
                    '</div>' +
                    '</div>';
            });
            html += '<button class="btn btn-primary" type="button" onclick="openSubcriptionModal()">Subcribe to Our Newslatter</button>' +
                '</div>' +
                '</div>';
            $("#mailchimp_lists").html(html);


        }, error: function (e) {
            notifyMe('Might have network issue !! Please Try Again Later..','error');
        }

    });

}function notifyMe(message,mode)
{
    if(mode=='success'){
        $.notify.addStyle('success', {
            html:'<div class="alert"><div class="notify_block warning update">'+
            '<div class="alert_text">'+
            '<p class="cross_icon">&#x2716;</p>'+
            '<h4>Success!</h4>'+
            '<p>'+message+'</p>'+
            '</div>'+
            '<div class="clearfix"></div>'+
            '</div></div>'
        });
        $.notify({
            title: ''
        },{
            autoHideDelay: 5000,
            clickToHide: true,
            style: 'success'
        });
    }else if(mode=='error'){
        $.notify.addStyle('error', {
            html:'<div class="alert"><div class="notify_block warning danger">'+
            '<div class="alert_text">'+
            '<p class="cross_icon">&#x2716;</p>'+
            '<h4>Error!</h4>'+
            '<p>'+message+'</p>'+
            '</div>'+
            '<div class="clearfix"></div>'+
            '</div></div>'
        });
        $.notify({
            title: ''
        },{
            autoHideDelay: 5000,
            clickToHide: true,
            style: 'error'
        });
    }else if(mode=='warning'){
        $.notify.addStyle('warning', {
            html:'<div class="alert"><div class="notify_block warning ">'+
            '<div class="alert_text">'+
            '<p class="cross_icon">&#x2716;</p>'+
            '<h4>Warning!</h4>'+
            '<p>'+message+'</p>'+
            '</div>'+
            '<div class="clearfix"></div>'+
            '</div></div>'
        });
        $.notify({
            title: ''
        },{
            autoHideDelay: 5000,
            clickToHide: true,
            style: 'warning'
        });
    }

}

$(document).ready(function(){
    $("#current_pwd").keyup(function(){
        var current_pwd = $("#current_pwd").val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'post',
            url:'/admin/check-current-password',
            data:{current_pwd:current_pwd},
            success:function(res){
                if(res=="false"){
                    $("#verifyCurrentPwd").html("Current Password is incorrect!")
                }else if(res == "true"){
                    $("#verifyCurrentPwd").html("Current Password is correct!")
                }
            },error:function(){
                alert("Error");
            }
        })
    });
    //Update CMS page status
    $(document).on("click",".updateCmsPageStatus",function(){
        var status = $(this).children("i").attr("status");
        var page_id = $(this).attr("page_id");
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/update-cms-page-status',
            data: {status: status, page_id: page_id},
            success: function(res) {
                if(res['status'] == 0){
                    $("#page-"+page_id).html("<i class='fas fa-toggle-off' style='color:gray' status='Inactive'></i>");
                } else if(res['status'] == 1) {
                    $("#page-"+page_id).html("<i class='fas fa-toggle-on' style='color:#3f6ed3' status='Active'></i>");
                }
            },
            error: function() {
                alert("Error");
            }
        });
    }); 
    
    //Update Sub admin status
    $(document).on("click", ".updateSubadminStatus", function() {
        var status = $(this).children("i").attr("status");
        var subadmin_id = $(this).attr("subadmin_id");
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/update-subadmin-status', // Correct AJAX URL
            data: { status: status, subadmin_id: subadmin_id },
            success: function(res) {
                if (res['status'] == 0) {
                    $("#subadmin-" + subadmin_id).html("<i class='fas fa-toggle-off' style='color:gray' status='Inactive'></i>");
                } else if (res['status'] == 1) {
                    $("#subadmin-" + subadmin_id).html("<i class='fas fa-toggle-on' style='color:#3f6ed3' status='Active'></i>");
                }
            },
            error: function() {
                alert("Error");
            }
        });
    });
    
    
    
    //Confirm the deletion of CMS Page
   /* $(document).on("click",".confirmDelete",function(){
        var name = $(this).attr('name');
        if(confirm('Are you sure to delete this '+name+'?')){
            return true;
        }
        return false;

    });*/
    //Confirm deletion with Sweetalert
    $(document).on("click",".confirmDelete",function(){
        var record = $(this).attr('record');
        var recordid = $(this).attr('recordid');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success'
              )
              window.location.href = "/admin/delete-"+record+"/"+recordid;
            }
          })

    });
});

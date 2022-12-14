$(document).ready(function(){
    $('#roles').on('change', function(){
        var role = $('#roles').val();
        $('#role_title').html(  $('#roles :selected').text().toUpperCase()+ ' Permissions');
        getPermissions(role);
    });
    $('#roles').trigger('change');

    $(document).on('click', '.rolecheck', function(e){
        var role = $('#roles').val();
        var perm = $(this).val();
        var act = '';
        if($(this).prop('checked'))
            act = 'add';
        else
            act = 'remove';
        var acturl = (act == 'add')?'add_permission':'remove_permission';
        setPermission(role, perm, acturl);
    });

    //add/removes permission to/from role
    function setPermission(role, perm, act){
        uurl = base_url + act;
        $.ajax({
            url: uurl+'/'+role+'/'+perm,
            type: 'get',
            dataType: 'json',
            beforeSend: function(){
                $('#loadingModal').modal({backdrop: 'static', keyboard: false});
            },
            success: function (data){
                //alert(JSON.stringify(data));
                if(data['status'] == 'success')
                    toastr.success(data['message'], 'Success:');
                else
                    toastr.error(data['message'], 'Error:');
            }
        }).done(function(data){$('#loadingModal').modal('hide');});
    }

    //get role permissions
    function getPermissions(role){
        $.ajax({
            url: base_url+'role_permissions/'+role,
            type: 'get',
            dataType: 'json',
            beforeSend: function(){
                $('#loadingModal').modal({backdrop: 'static', keyboard: false});
            },
            success: function (data){
                //alert(JSON.stringify(data));
                $('role_permissions').empty();
                var perm = '';
                var rps = [];
                $.each(data.permissions, function(a,b){
                    rps.push(b.id);
                });
                $.each(data.all_permissions, function(a,b){
                    if(rps.includes(b.id))
                        perm += '<div class="col-md-4 form-group"> <input type="checkbox" class="rolecheck" checked name="pm" id="pm" value="'+b.id+'" /> '+b.name+'</div>';
                    else
                        perm += '<div class="col-md-4 form-group"> <input type="checkbox" class="rolecheck" name="pm" id="pm" value="'+b.id+'" /> '+b.name+'</div>';
                });
                $('#role_permissions').html(perm);
                $('*').scrollTop(0);
            }
        }).done(function(data){
            $('#loadingModal').modal('hide');
        }).fail(function(xhr, st, er){
            //alert(xhr+st+er);
        });
    }
});
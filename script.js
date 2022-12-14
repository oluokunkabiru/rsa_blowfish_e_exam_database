$(document).ready(function() {
    // customer login
    $('#userlogin').click(function(event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'authenticate/login.php',
                data: $('#userloginform').serialize(),
                success: function(data) {
                    var result = data;
                    $("#loginerror").html(result);
                    if (result == "student") {
                        $("#loginerror").hide();
                        window.location.assign('users/students');
                    }
                    if (result == "teacher") {
                        $("#loginerror").hide();
                        window.location.assign('users/teachers');
                    }
                    if (result == "admin") {
                        $("#loginerror").hide();
                        window.location.assign('users/admin');
                    }
                }
            });
        })
        // end of login

    // add new class
    $('#newclassbtn').click(function(event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'addclass.php',
                data: $('#newclassform').serialize(),
                success: function(data) {
                    var result = data;
                    $(".error").html(result);
                    if (result == "<span class='text-success'>Class added successfully</span>") {
                        // alert(result);
                        window.location.assign('manageclass.php');
                    }
                }
            })
        })
        // end of new class
        // add new class
    $('#addtimebtn').click(function(event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'addtime.php',
            data: $('#addtimeform').serialize(),
            success: function(data) {
                var result = data;
                $(".error").html(result);
                if (result == "<span class='text-success'>Time added successfully</span>") {
                    // alert(result);
                    window.location.assign('manageexamination.php');
                }
            }
        })
    })

    $('#addnewresult').click(function(event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'addresult.php',
            data: $('#addresultform').serialize(),
            success: function(data) {
                var result = data;
                $(".error").html(result);
                if (result == "<span class='text-success'>Result added successfully</span>") {
                    // alert(result);
                    window.location.reload();
                }
            }
        })
    })

    $('#addtimetothisuserbtn').click(function(event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'addtimetouser.php',
                data: $('#addtimethisuserform').serialize(),
                success: function(data) {
                    var result = data;
                    $(".error").html(result);
                    if (result == "<span class='text-success'>Time added successfully</span>") {
                        // alert(result);
                        window.location.assign('manageexamination.php');
                    }
                }
            })
        })
        // table
        // $(function () {
    $('#tables').DataTable({
        dom: 'Bfrtip',
        // paging: false,

        buttons: [
            {
                extend: 'print',
                // extend: "stripHtml",
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions:{
                    stripHtml:false
                },
            },

            {
                extend: 'pdfHtml5',
                // extend: "stripHtml",
                // orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions:{
                    // stripHtml:false

                },
            },
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
        ]
    });
    // });
    // $("#tables").dataTable({
                
    //     dom: 'Bfrtip',
    //     paging: false,
        
    //     buttons: [
    //             {
    //                 extend: 'print',
    //                 // extend: "stripHtml",
    //                 orientation: 'landscape',
    //                 pageSize: 'LEGAL',
    //                 exportOptions:{
    //                     stripHtml:false
    //                 },
    //             },

    //         'copy', 'excel'
    //     ],

       
    // });
    // });



    // delete class confirmation
    $('#deleteresult').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).attr('deleteresultconfirm');
        var key = $(e.relatedTarget).attr('key');
        $.ajax({
            type: 'post',
            url: '../confirm.php',
            data: 'deleteresult=' + id+"&key="+key,
            success: function(data) {
                $('.deleteresult').html(data);
            }
        })
    })

    // delete
    //class edit confirm
    $('#editresult').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).attr('editresult');
        var key = $(e.relatedTarget).attr('key');
        // alert(id);
        $.ajax({
            type: 'post',
            url: '../confirm.php',
            data: 'editresult=' + id+"&key="+key,
            success: function(data) {
                $('.editresult').html(data);
            }
        })
    })


    // delete class confirmation
    $('#deleteclass').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).attr('deleteclassconfirm');
        $.ajax({
            type: 'post',
            url: 'confirm.php',
            data: 'deleteclass=' + id,
            success: function(data) {
                $('.deleteclass').html(data);
            }
        })
    })

    // delete
    //class edit confirm
    $('#editclass').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).attr('editclass');
        // alert(id);
        $.ajax({
            type: 'post',
            url: 'confirm.php',
            data: 'editclass=' + id,
            success: function(data) {
                $('.editclass').html(data);
            }
        })
    })

    // edit class
    $('#btneditclassconfir').click(function(event) {
            alert(1);
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'addclass.php',
                data: $('#newclassform').serialize(),
                success: function(data) {
                    var result = data;
                    $(".error").html(result);
                    if (result == "<span class='text-success'>Class added successfully</span>") {
                        // alert(result);
                        window.location.assign('manageclass.php');
                    }
                }
            })
        })
        // end of class confirm

    // add new student
    $('#newstudentfileform').submit(function(e) {
        $('head').append('<style> .page-loader .loader p:before {content: "Uploading";}</style>')
        e.preventDefault();
        var datas = new FormData(this);
        $.ajax({
            type: 'POST',
            url: 'addstudent.php',
            data: datas,
            contentType: false,
            cache: false,
            processData: false,  
            dataType: "json",

            success: function(data) {
                // var result = data;
                // $(".error").html(result);
                console.log(data);
                if (data.status == "error") {
                    // alert("hel")
                    // console.log("hello");
                    $.each(JSON.parse(data.data), function(key, value) {
                        $('.' +key).html(value);

                        // console.log(key + " : " + value);
                    })
                }
                if (data.status == "success") {
                    $(".error").html(data.message);
                }

                if (data.url ){
                        window.location.assign(data.url);
                }


                // if (result == "<span class='text-success'>Student added successfully</span>") {
                //     // alert(result);
                //     window.location.assign('managestudent.php');
                // }
            },
            beforeSend: function() {
                pageLoader.show();
            },

            complete: function() {
                pageLoader.hide();
            }
        })
    })
  
    $('#newstudentform').submit(function(e) {
            e.preventDefault();
            var datas = new FormData(this);
            $.ajax({
                type: 'POST',
                url: 'addstudent.php',
                data: datas,
                contentType: false,
                cache: false,
                processData: false, 
                dataType: "json",
               
                success: function(data) {
                    // var result = data;
                    // console.log('hi');
                    console.log(data);
                    if (data.status == "error") {
                        // alert("hel")
                        // console.log("hello");
                        $.each(JSON.parse(data.data), function(key, value) {
                            $('.' +key).html(value);

                            console.log(key + " : " + value);
                        })
                    }
                    if (data.status == "success") {
                        $(".error").html(data.message);
                    }

                    if (data.url ){
                            window.location.assign(data.url);
                    }


                    // $(".error").html(result);
                    // if (result == "<span class='text-success'>Student added successfully</span>") {
                    //     // alert(result);
                    //     window.location.assign('managestudent.php');
                    // }
                }
            })
        })
        // end of new class

    //confirm delete student
    $('#deletestudent').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).attr('deletestudentconfirm');
        $.ajax({
            type: 'post',
            url: 'confirm.php',
            data: 'deletestudent=' + id,
            success: function(data) {
                $('.deletestudent').html(data);
            }
        })
    })

    //confirm edit student
    $('#editstudent').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).attr('editstudentconfirm');
        $.ajax({
            type: 'post',
            url: 'confirm.php',
            data: 'editstudent=' + id,
            success: function(data) {
                $('.editstudent').html(data);
            }
        })
    })




    // add new staff
    $('#newstaffform').submit(function(e) {
        e.preventDefault();
        var datas = new FormData(this);
        $.ajax({
            type: 'POST',
            url: 'addstaff.php',
            data: datas,
            contentType: false,
            cache: false,
            processData: false,                
            success: function(data) {
                    var result = data;
                    $(".error").html(result);
                    if (result == "<span class='text-success'>Staff added successfully</span>") {
                        // alert(result);
                        window.location.assign('index.php');
                    }
                }
            })
        })
        // end of new class


        $('#newstafffileform').submit(function(e) {
            e.preventDefault();
            $('head').append('<style> .page-loader .loader p:before {content: "Uploading";}</style>')
            var datas = new FormData(this);
            $.ajax({
                type: 'POST',
                url: 'addstaff.php',
                data: datas,
                contentType: false,
                cache: false,
                processData: false,                
                success: function(data) {
                        var result = data;
                        $(".error").html(result);
                        if (result == "<span class='text-success'>Staff added successfully</span>") {
                            // alert(result);
                            window.location.assign('index.php');
                        }
                    },
                beforeSend: function() {
                    pageLoader.show();
                },
    
                complete: function() {
                    pageLoader.hide();
                }
                })
            })

    //confirm delete student
    $('#deletestaff').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).attr('deletestaffconfirm');
        $.ajax({
            type: 'post',
            url: '../confirm.php',
            data: 'deletestaff=' + id,
            success: function(data) {
                $('.deletestaff').html(data);
            }
        })
    })

    //confirm edit student
    $('#editstaff').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).attr('editstaffconfirm');
        $.ajax({
            type: 'post',
            url: '../confirm.php',
            data: 'editstaff=' + id,
            success: function(data) {
                $('.editstaff').html(data);
            }
        })
    })



    // subject 
    // add new subject
    $('#newsubjectbtn').click(function(event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'addsubject.php',
                data: $('#newsubjectform').serialize(),
                success: function(data) {
                    var result = data;
                    $(".error").html(result);
                    if (result == "<span class='text-success'>Subject added successfully</span>") {
                        // alert(result);
                        window.location.assign('managesubject.php');
                    }
                }
            })
        })
        //confirm delete student
    $('#deletesubject').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).attr('deletesubjectconfirm');
        $.ajax({
            type: 'post',
            url: 'confirm.php',
            data: 'deletesubject=' + id,
            success: function(data) {
                $('.deletesubject').html(data);
            }
        })
    })

    //confirm edit student
    $('#editsubject').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).attr('editsubject');
        $.ajax({
            type: 'post',
            url: 'confirm.php',
            data: 'editsubject=' + id,
            success: function(data) {
                $('.editsubject').html(data);
            }
        })
    })

    // new examinations form
    $('#newexaminationbtn').click(function(event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'addnewexamination.php',
            data: $('#newexaminationform').serialize(),
            dataType: "JSON",
            success: function(data) {
                // var result = data;
                if(data.status=="error"){
                    // var  errors ="";
                    $.each(JSON.parse(data.data), function(key, value) {
                        $('.' +key).html(value);                    
                    })
                }

                if(data.status=="success"){
                 
                // $(".error").html(result);
                // if (result == "<span class='text-success'>Examination added successfully</span>") {
                //     // alert(result);
                    window.location.assign('manageexamination.php');
                }
            }
        })
    })

    // 
    //confirm edti student
    $('#editexamination').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).attr('editexamination');
        $.ajax({
            type: 'post',
            url: '../confirm.php',
            data: 'editexamination=' + id,
            success: function(data) {
                $('.editexamination').html(data);
            }
        })
    })

    //confirm delete student
    $('#deleteexamination').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).attr('deleteexamination');
        $.ajax({
            type: 'post',
            url: '../confirm.php',
            data: 'deleteexamination=' + id,
            success: function(data) {
                $('.deleteexamination').html(data);
            }
        })
    })




    // summernote
    var mybutton = function(content) {
        var ui = $.summernote.ui;
        var buttonvb = ui.button({
            contents: "<i class='fa fa-user'>user</i>",
            tooltip: "vboy",
            click: function() {
                alert("good village boy");
            }

        })
        return buttonvb.render();
    }
    $('.textarea').summernote({
        height: 150,
        toolbar: [
            // ['vb', ['goodvb']],
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear', 'superscript', 'subscript']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'math']],
            ['view', ['fullscreen', 'codeview', 'help', 'undo', 'redo']]
        ],
        buttons: {
            goodvb: mybutton
        },
        callbacks: {
            onImageUpload: function(image) {
                editor = $(this);
                uploadImageContent(image[0], editor);
            },
            // },
            // callbacks: {
            onMediaDelete: function(target) {
                deleteFile(target[0].src);
            }
        },
    })

    function uploadImageContent(image) {
        var data = new FormData();
        data.append("image", image);
        $.ajax({
            url: '../../fileupload.php',
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            type: "post",
            success: function(url) {
                var image = $('<img>').attr('src', url);
                $('.textarea').summernote("insertNode", image[0]);
            },
            error: function(data) {
                console.log(data);
            }
        });
    }

    function deleteFile(src) {
        $.ajax({
            data: { src: src },
            type: "POST",
            url: "../../questionfiledelete.php",
            cache: false,
            success: function(response) {
                // alert(response);
            }
        })
    }


    // questions
    // $('#formupload').submit(function(e){
    $('#newexaminationquestionform').submit(function(e) {
        // alert(xmlh.status);
        e.preventDefault();
        var datas = new FormData(this);

        $.ajax({
            type: 'POST',
            url: 'addnewquestions.php',
            data: datas,
            contentType: false,
            cache: false,
            processData: false,
            dataType: "JSON",
            success: function(response) {

                if(response.status=="error"){

                    $('.error').html(response.error);

                }
                // if (response.error != null) {
                //     $('.error').html(response.error);
                // }
                if (response.ok != null) {
                    window.location.assign(response.ok);
                }
            },

        });

    })

    $('#fileformquestion').submit(function(e) {
        // alert(xmlh.status);
        $('head').append('<style> .page-loader .loader p:before {content: "Uploading";}</style>')
        // pageLoader.show();

        e.preventDefault();
        var datas = new FormData(this);

        $.ajax({
            type: 'POST',
            url: 'excelfileuploading.php',
            data: datas,
            contentType: false,
            cache: false,
            processData: false,
            dataType:"JSON",
            success: function(response) {
                if(response.status=="error"){
                    var  errors ="";
                    $.each(JSON.parse(response.data), function(key, value) {
                        errors+="<tr class='text-danger'><td>"+(parseInt(key)+1)+"</td><td>"+ value +"</td></tr>"
                    })
                    $('.excelfilemsg').html(errors);
                    $('.msg').removeClass('text-success');
                    $('.msg').addClass('text-danger');
                    $('.msg').html(response.message);
                }

                if(response.status=="success"){
                    var  errors ="";
                    $.each(JSON.parse(response.data), function(key, value) {
                        errors+="<tr class='text-success'><td>"+(parseInt(key)+1)+"</td><td>"+ value +"</td></tr>"
                    })
                    $('.excelfilemsg').html(errors);
                    $('.msg').removeClass('text-danger');
                    $('.msg').addClass('text-success');
                    $('.msg').html(response.message);

                }

                if (response.url){
                        window.location.assign(response.url);
                }
                // if (response) {
                //     $('.excelfileerror').html(response);
                // }
                // if (response.ok != null) {
                //     window.location.assign(response.ok);
                // }
            },
        beforeSend: function() {
            pageLoader.show();
        },

        complete: function() {
            pageLoader.hide();
        }

        });

    })



    $('#editexaminationquestionform').submit(function(e) {
        e.preventDefault();
        var datas = new FormData(this);
        $.ajax({
            type: 'POST',
            url: 'editquestion.php',
            data: datas,
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            success: function(results) {
                $('.questionediterror').html(results);
                if (results.error != null) {
                    $('.questionediterror').html(results.error);
                }
                if (results.vb != null) {
                    $('.questionediterror').html(results.vb);
                    window.location.assign(results.vb);
                }
            },

        });

    })

    // $('#formupload').submit(function(e){
    $('#editexaminationquestionfor').submit(function(e) {
        // alert(xmlh.status);
        e.preventDefault();
        var datas = new FormData(this);

        $.ajax({
            type: 'POST',
            url: 'editexaminationquestion.ph',
            data: datas,
            contentType: false,
            cache: false,
            processData: false,
            dataType: "JSON",
            success: function(response) {
                alert(response.error);
                if (response.error != null) {
                    $('.questionediterro').append(response.error);
                }
                if (response.ok != null) {
                    $('.questionediterro').append(response.ok);
                    window.location.assign(response.ok);
                }
            },

        });

    })


    // delete question
    $('#deletequestion').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).attr('deletequestion');
        $.ajax({
            type: 'post',
            url: '../confirm.php',
            data: 'deletequestion=' + id,
            success: function(data) {
                $('.deletequestion').html(data);
            }
        })
    })

    // activate question
    // delete question
    $('#activateexamination').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).attr('activateexamination');
        $.ajax({
            type: 'post',
            url: '../confirm.php',
            data: 'activateexamination=' + id,
            success: function(data) {
                $('.activateexamination').html(data);
            }
        })
    })


    $('#deactivateexamination').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).attr('deactivateexamination');
        $.ajax({
            type: 'post',
            url: '../confirm.php',
            data: 'deactivateexamination=' + id,
            success: function(data) {
                $('.deactivateexamination').html(data);
            }
        })
    })
    $('#stopexamination').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).attr('stopexamination');
        $.ajax({
            type: 'post',
            url: '../confirm.php',
            data: 'stopexamination=' + id,
            success: function(data) {
                $('.stopexamination').html(data);
            }
        })
    })


})
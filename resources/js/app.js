require('./bootstrap');

var todolist = {
    init: function(){
        todolist.common.init();
        todolist.category.init();
        todolist.list.init();
    },
    common:{
        init:function(){
            todolist.common.validation();
            todolist.common.toggle();
        },
        toggle: function(){
            $(document).on("click", '.toggleModal', function(e){
                console.log(e);
                console.log(e.target.value);
                $("#" + e.target.value).modal("show");

                console.log("here");
            });
        },
        validation: function(){
        },
        download: function(){

        }
    },
    category:{
        init:function(){
            todolist.category.post();
            todolist.category.update();
            todolist.category.delete();
        },
        post: function(){
            $(document).on("click", '#category .post', function(e){
                e.preventDefault();

                if ( $('#category .post')[0].checkValidity()){
                    data_array = $('#category .post').serializeArray();
                    data = [];

                    $.each(data_array, function(key,val){
                        data[val.name] = val.value;
                    });

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'POST',
                        url: $('body').data('baseurl')+ '/category',
                        dataType:'json',
                        data:{
                            'name' : data.name
                        },
                        success:function(store) {
                            $("#category").modal("hide");
                            location.reload();
                        },
                        error:function(reject) {
                           console.log(reject)
                        }
                    });
                } else{
                    //Validate Form
                    $('#category .post')[0].reportValidity()
                }
            })

        },
        update: function(){
            $(document).on("click", '#search-category', function(e) {
                let search = $('#search_input')[0].value
                console.log(search);
                e.preventDefault();

                if(search == ''){
                    window.location.assign($('body').data('baseurl') + '/home');
                }else{
                    window.location.assign($('body').data('baseurl') + '/home?search='+search);
                }
            })

        },
        delete: function(){
        }
    },
    list:{
        init:function(){
            todolist.list.post();
            todolist.list.update();
            todolist.list.delete();
            todolist.list.downloadFile();
        },
        post: function(){
            let new_file = ''
            let new_file_name = ''
            let new_file_type = ''

            $(document).on("change", ".uploadFile", function(e) {
                var reader = new FileReader();
                new_file = e.target.files[0];

                if (new_file) {
                    if(new_file.size > 100000){
                        console.log(new_file.size);
                        alert("File size too big!")
                        $('.uploadFile')[0].value = ''
                    }else{
                        let file = new_file;
                        let reader = new FileReader();
                        reader.onloadend = function() {
                            new_file = reader.result;
                        }
                        new_file_name = new_file.name;
                        new_file_type = new_file.type;
                        reader.readAsDataURL(file);
                    }
                }
            });

            $(document).on("click", '#postTask', function(e){
                e.preventDefault();

                if ( $('#task .post')[0].checkValidity()){
                    data_array = $('#task .post').serializeArray();
                    data = [];

                    $.each(data_array, function(key,val){
                        data[val.name] = val.value;
                    });

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'POST',
                        url: $('body').data('baseurl')+ '/todolist',
                        dataType:'json',
                        data:{
                            'title' : data.title,
                            'category' : data.category,
                            'due_on' : data.due_on,
                            'file': new_file,
                            'file_name' : new_file_name,
                            'file_type' : new_file_type,
                        },
                        success:function(store) {
                            $("#task").modal("hide");
                            swal("Task have been added!")
                                .then((value) => {
                                    location.reload();
                                });
                        },
                        error:function(reject) {
                            console.log(reject)
                        }
                    });
                } else{
                    //Validate Form
                    $('#task .post')[0].reportValidity()
                }
            })

        },
        downloadFile: function(){
            $(document).on("click", '.viewFile', function(e) {
                e.preventDefault();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'GET',
                    url: $('body').data('baseurl')+ '/todolist/getfile/' + e.target.value,
                    success:function(store) {
                        console.log(store.data)
                    },
                    error:function(reject) {
                        console.log(reject)
                    }
                });
            })
        },
        update: function(){
            let new_file = ''
            let new_file_name = ''
            let new_file_type = ''

            $(document).on("change", ".uploadFile", function(e) {
                var reader = new FileReader();
                new_file = e.target.files[0];
                if (new_file) {
                    if(new_file.size > 100000){
                        console.log(new_file.size);
                        alert("File size too big!")
                        $('.uploadFile')[0].value = ''
                    }else{
                        let file = new_file;
                        let reader = new FileReader();
                        reader.onloadend = function() {
                            new_file = reader.result;
                        }
                        new_file_name = new_file.name;
                        new_file_type = new_file.type;
                        reader.readAsDataURL(file);
                    }
                }
            });

            $('#taskImage').on('show.bs.modal', function(e) {
                const id = $(e.relatedTarget).data('id');
                console.log(e);
                $(e.currentTarget).find('input[name="id"]').val(id);
            });

            $(document).on("click", '#postImageTask', function(e){
                e.preventDefault();

                if ( $('#taskImage .post')[0].checkValidity()){
                    data_array = $('#taskImage .post').serializeArray();
                    data = [];

                    $.each(data_array, function(key,val){
                        data[val.name] = val.value;
                    });

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'POST',
                        url: $('body').data('baseurl')+ '/todolist/uploadfile/' + data.id,
                        dataType:'json',
                        data:{
                            'file': new_file,
                            'file_name' : new_file_name,
                            'file_type' : new_file_type,
                        },
                        success:function(store) {
                            $("#task").modal("hide");
                            location.reload();
                        },
                        error:function(reject) {
                            console.log(reject)
                        }
                    });
                } else{
                    //Validate Form
                    $('#taskImage .post')[0].reportValidity()
                }
            })

            $('#updateTask').on('show.bs.modal', function(e) {
                const id = $(e.relatedTarget).data('id');
                $(e.currentTarget).find('input[name="id"]').val(id);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'GET',
                    url: $('body').data('baseurl') + '/todolist/' + id,
                    success:function(store) {
                        $(e.currentTarget).find('input[name="id"]').val(id);
                        $(e.currentTarget).find('input[name="title"]').val(store.data.title);
                        $(e.currentTarget).find('input[name="category"]').val(store.data.category.name);
                        $(e.currentTarget).find('input[name="due_on"]').val(store.data.format_date);
                    },
                    error:function(reject) {
                        if(reject){
                            alert(reject.data);
                        }
                    }
                });
            });

            $(document).on("click", '#postUpdateTask', function(e){
                e.preventDefault();

                if ( $('#updateTask .post')[0].checkValidity()){
                    data_array = $('#updateTask .post').serializeArray();
                    data = [];

                    $.each(data_array, function(key,val){
                        data[val.name] = val.value;
                    });
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'PUT',
                        url: $('body').data('baseurl')+ '/todolist/' + data.id,
                        dataType:'json',
                        data:{
                            'title' : data.title,
                            'category' : data.category,
                            'due_on' : data.due_on,
                            'remove_file' : data.remove_file ? 1 : 0,
                            'file': new_file,
                            'file_name' : new_file_name,
                            'file_type' : new_file_type,
                        },
                        success:function(store) {
                            $("#updateTask").modal("hide");
                            swal("Task have been updated!")
                                .then((value) => {
                                    location.reload();
                                });
                        },
                        error:function(reject) {
                            console.log(reject)
                        }
                    });
                } else{
                    //Validate Form
                    $('#updateTask .post')[0].reportValidity()
                }
            })

            $(document).on("click", '.task-check', function(e){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: $('body').data('baseurl')+ '/todolist/checked/' + parseInt(e.target.value),
                    dataType:'json',
                    data:{
                        'check' : e.target.checked,
                    },
                    success:function(store) {
                        html = "";
                        $(".task-" + e.target.value + " .dynamic-label").find('label[name="due_on"]').remove()
                        if(e.target.checked){
                            html="<label class=\"grey-out\" name=\"due_on\">Completed</label>";
                            $(".task-" + e.target.value).find('label[name="title"]').addClass('grey-out')
                        }else{
                            switch(store.data.deadline) {
                                case 'Today':
                                    html="<label style=\"color:#2CC6AB\">" + store.data.deadline + "</label>";

                                    break;
                                case 'Tomorrow':
                                    html="  <label style=\"color:#AB5DE6\">" + store.data.deadline + "</label>";
                                    break;
                                case 'Overdue':
                                    html="<label style=\"color:#f35959\" name=\"due_on\">" + store.data.deadline +
                                        "<i class=\"fa fa-circle\" style=\"padding-left:5px;color:#f35959\"></i>\n" +
                                        "</label>";
                                    break;
                                default:
                                    html=" <label name=\"due_on\">" + store.data.deadline + "</label>";
                            }
                            $(".task-" + e.target.value).find('label[name="title"]').removeClass('grey-out');

                        }
                        $(".task-" + e.target.value + " .dynamic-label").append(html);
                    },
                    error:function(reject) {
                        console.log(reject)
                    }
                });
            })
        },
        delete: function(){
            $(document).on("click", '.deleteTask', function(e){
                e.preventDefault();

                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this task!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                                },
                                method: 'DELETE',
                                url: $('body').data('baseurl')+ '/todolist/' + parseInt(e.target.name),
                                success:function(store) {
                                    $( ".task-"+ parseInt(e.target.name)).remove();
                                    swal("Poof! Task has been deleted!");
                                },
                                error:function(reject) {
                                    console.log(reject)
                                    swal("Please contact your administrator", {
                                        icon: "error",
                                    });
                                }
                            });

                        }
                    });
            })
        }
    }
}

var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var content = this.nextElementSibling;
        if (content.style.maxHeight){
            content.style.maxHeight = null;
            content.style.overflow = 'hidden';
        } else {
            content.style.maxHeight = content.scrollHeight + "px";
            content.style.overflow = 'unset';
        }
    });
}

$(document).ready(function () {
    todolist.init();
});
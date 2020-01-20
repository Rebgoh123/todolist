@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">

                <div class="header">

                    <div class="form-group col-3">
                    <button type="button" value="task" class="btn btn-primary toggleModal">Add new task</button>
                    </div>

                        <div class="form-group col-9">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Search for Category" value="{{$search_raw}}" id="search_input" aria-label="Search for Category">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" id="search-category" type="button">Search</button>
                                </div>
                            </div>
                        </div>

                </div>




                <div class="card-body">
                    <button class="collapsible active"> Today </button>
                    <div class="c-content" style="max-height: 100%">
                        @if (count($today)>0)
                        @foreach ($today as $list)
                                <form class="form-inline task-{{$list->id}}">
                                    <div class="form-group col-12 col-sm-12 col-lg-7 mobile-padding">
                                        <div class="pretty p-icon p-smooth">
                                            <input class="task-check" value="{{$list->id}}" type="checkbox" @if($list->checked === 1) checked @endif/>
                                            <div class="state">
                                                <i class="icon fa fa-check"></i>
                                                @if($list->checked === 1)
                                                    <label name="title" class="grey-out"> {{$list->title}}</label>
                                                @else
                                                    <label name="title" class=""> {{$list->title}}</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-3 col-sm-2 col-lg-1 uploadFilesBtn">
                                        @if($list->upload_file === 1)
                                            <a href="{{'/todolist/getfile/' .$list->id }}">  <button type="button" class="btn btn-purple btn-sm" >View</button> </a>
                                        @else
                                            <button type="button" class="btn btn-pink btn-sm" data-toggle="modal" data-target="#taskImage"
                                                    data-id="{{$list->id}}">Upload</button>

                                        @endif
                                    </div>
                                    <div class="form-group col-5 col-sm-4 col-lg-2 category">
                                        @if($list->category->name)
                                            <button class="color-{{$list->category->id}} btn btn-sm">{{$list->category->name}}</button>
                                        @endif
                                    </div>
                                    <div class="form-group col-3 col-sm-2 col-lg-2 category" style="justify-content: flex-end;">
                                        <div class="dynamic-label">
                                            @if($list->checked === 0)
                                                @switch($list->deadline)
                                                    @case('Today')
                                                    <label style="color:#2CC6AB" name="due_on">{{$list->deadline}}</label>
                                                    @break
                                                    @case('Tomorrow')
                                                    <label style="color:#AB5DE6" name="due_on">{{$list->deadline}}</label>
                                                    @break
                                                    @case('Overdue')
                                                    <label style="color:#f35959" name="due_on">{{$list->deadline}}
                                                        <i class="fa fa-circle" style="padding-left:5px;color:#f35959"></i>
                                                    </label>
                                                    @break
                                                    @default
                                                    <label name="due_on">{{$list->deadline}}</label>
                                                @endswitch
                                            @else
                                                <label class="grey-out" name="due_on">Completed</label>
                                            @endif
                                        </div>

                                            <div class="dropdown show">
                                                <a class="dropdown-toggle" href="#" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v" style="padding-left: 10px;"></i>
                                                </a>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#updateTask" data-id="{{$list->id}}">Edit</a>
                                                    <a class="dropdown-item deleteTask" name="{{$list->id}}" href="#">Delete</a>
                                                </div>
                                            </div>
                                    </div>
                                </form>
                            @endforeach
                        @endif
                    </div>

                    <button class="collapsible active"> Tomorrow </button>
                    <div class="c-content" style="max-height: 100%">
                        @if (count($tomorrow)>0)
                            @foreach ($tomorrow as $list)
                                <form class="form-inline task-{{$list->id}}">
                                    <div class="form-group col-12 col-sm-12 col-lg-7 mobile-padding">
                                        <div class="pretty p-icon p-smooth">
                                            <input class="task-check" value="{{$list->id}}" type="checkbox" @if($list->checked === 1) checked @endif/>
                                            <div class="state">
                                                <i class="icon fa fa-check"></i>
                                                @if($list->checked === 1)
                                                    <label name="title" class="grey-out"> {{$list->title}}</label>
                                                @else
                                                    <label name="title" class=""> {{$list->title}}</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-3 col-sm-2 col-lg-1 uploadFilesBtn">
                                        @if($list->upload_file === 1)
                                            <a href="{{'/todolist/getfile/' .$list->id }}">  <button type="button" class="btn btn-purple btn-sm" >View</button> </a>
                                        @else
                                            <button type="button" class="btn btn-pink btn-sm" data-toggle="modal" data-target="#taskImage"
                                                    data-id="{{$list->id}}">Upload</button>

                                        @endif
                                    </div>
                                    <div class="form-group col-5 col-sm-4 col-lg-2 category">
                                        @if($list->category->name)
                                            <button class="color-{{$list->category->id}} btn btn-sm">{{$list->category->name}}</button>
                                        @endif
                                    </div>
                                    <div class="form-group col-3 col-sm-2 col-lg-2 category" style="justify-content: flex-end;">
                                        <div class="dynamic-label">
                                            @if($list->checked === 0)
                                                @switch($list->deadline)
                                                    @case('Today')
                                                    <label style="color:#2CC6AB" name="due_on">{{$list->deadline}}</label>
                                                    @break
                                                    @case('Tomorrow')
                                                    <label style="color:#AB5DE6" name="due_on">{{$list->deadline}}</label>
                                                    @break
                                                    @case('Overdue')
                                                    <label style="color:#f35959" name="due_on">{{$list->deadline}}
                                                        <i class="fa fa-circle" style="padding-left:5px;color:#f35959"></i>
                                                    </label>
                                                    @break
                                                    @default
                                                    <label name="due_on">{{$list->deadline}}</label>
                                                @endswitch
                                            @else
                                                <label class="grey-out" name="due_on">Completed</label>
                                            @endif
                                        </div>

                                        <div class="dropdown show">
                                            <a class="dropdown-toggle" href="#" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v" style="padding-left: 10px;"></i>
                                            </a>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <a class="dropdown-item" data-toggle="modal" data-target="#updateTask" data-id="{{$list->id}}">Edit</a>
                                                <a class="dropdown-item deleteTask" name="{{$list->id}}" href="#">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @endforeach
                        @endif
                    </div>

                    <button class="collapsible active"> Upcoming </button>
                    <div class="c-content" style="max-height: 100%">
                        @if (count($other)>0)
                            @foreach ($other as $list)
                                <form class="form-inline task-{{$list->id}}">
                                    <div class="form-group col-12 col-sm-12 col-lg-7 mobile-padding">
                                        <div class="pretty p-icon p-smooth">
                                            <input class="task-check" value="{{$list->id}}" type="checkbox" @if($list->checked === 1) checked @endif/>
                                            <div class="state">
                                                <i class="icon fa fa-check"></i>
                                                @if($list->checked === 1)
                                                    <label name="title" class="grey-out"> {{$list->title}}</label>
                                                @else
                                                    <label name="title" class=""> {{$list->title}}</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-3 col-sm-2 col-lg-1 uploadFilesBtn">
                                        @if($list->upload_file === 1)
                                            <a href="{{'/todolist/getfile/' .$list->id }}">  <button type="button" class="btn btn-purple btn-sm" >View</button> </a>
                                        @else
                                            <button type="button" class="btn btn-pink btn-sm" data-toggle="modal" data-target="#taskImage"
                                                    data-id="{{$list->id}}">Upload</button>

                                        @endif
                                    </div>
                                    <div class="form-group col-5 col-sm-4 col-lg-2 category">
                                        @if($list->category->name)
                                            <button class="color-{{$list->category->id}} btn btn-sm">{{$list->category->name}}</button>
                                        @endif
                                    </div>
                                    <div class="form-group col-3 col-sm-2 col-lg-2 category" style="justify-content: flex-end;">
                                        <div class="dynamic-label">
                                            @if($list->checked === 0)
                                                @switch($list->deadline)
                                                    @case('Today')
                                                    <label style="color:#2CC6AB" name="due_on">{{$list->deadline}}</label>
                                                    @break
                                                    @case('Tomorrow')
                                                    <label style="color:#AB5DE6" name="due_on">{{$list->deadline}}</label>
                                                    @break
                                                    @case('Overdue')
                                                    <label style="color:#f35959" name="due_on">{{$list->deadline}}
                                                        <i class="fa fa-circle" style="padding-left:5px;color:#f35959"></i>
                                                    </label>
                                                    @break
                                                    @default
                                                    <label name="due_on">{{$list->deadline}}</label>
                                                @endswitch
                                            @else
                                                <label class="grey-out" name="due_on">Completed</label>
                                            @endif
                                        </div>

                                        <div class="dropdown show">
                                            <a class="dropdown-toggle" href="#" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v" style="padding-left: 10px;"></i>
                                            </a>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <a class="dropdown-item" data-toggle="modal" data-target="#updateTask" data-id="{{$list->id}}">Edit</a>
                                                <a class="dropdown-item deleteTask" name="{{$list->id}}" href="#">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="task" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input placeholder="Task" type="text" class="form-control" name="title" required autofocus>
                        </div>

                        <div class="form-group">
                            <input placeholder="Category" type="text" class="form-control" name="category" autofocus>
                        </div>

                        <div class="form-group">
                            <input class="form-control" type="date" name="due_on">
                        </div>

                        <div class="form-group">
                            <input type="file" class="form-control-file uploadFile">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button class="btn btn-outline-secondary" id="postTask">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="taskImage" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" class="form-control" name="id">

                        <div class="form-group">
                            <input type="file" class="form-control-file uploadFile">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button class="btn btn-outline-secondary" id="postImageTask">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateTask" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="post">
                    @csrf
                    <input type="hidden" class="form-control" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <input placeholder="Task" type="text" class="form-control" name="title" required autofocus>
                        </div>

                        <div class="form-group">
                            <input placeholder="Category" type="text" class="form-control" name="category">
                        </div>

                        <div class="form-group">
                            <input class="form-control" type="date" name="due_on">
                        </div>

                        <div class="form-group ">
                            <span class="form-file">To replace existing file</span>
                            <input type="file" class="form-control-file uploadFile">
                        </div>

                        <div class="form-group">
                            <span class="form-file">To remove file</span>
                            <input type="checkbox" name="remove_file">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button class="btn btn-outline-secondary" id="postUpdateTask">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

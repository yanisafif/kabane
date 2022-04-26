@extends('layout.master')

@section('title')User Profile {{$user->name}}
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/photoswipe.css')}}">
@endpush

@section('content')

	<div class="container-fluid">
	    <div class="user-profile">
	        <div class="row">
	            <!-- user profile header start-->
	            <div class="col-sm-12">
	                <div class="card profile-header">
	                    <img class="img-fluid bg-img-cover" src="{{asset('assets/images/user-profile/'.random_int(1,12).'.jpg')}}" alt="" />

	                    <div class="userpro-box">
	                        <div class="img-wrraper">
                                <div class="avatar"><img class="img-fluid" alt="" src="{{ empty(auth()->user()->path_image) ? asset('assets/images/dashboard/1.png') : asset('avatars/'.auth()->user()->path_image) }}" /></div>

                                @if (auth()->user()->id == $user->id)
                                    <a class="icon-wrapper" href="/user/profile/update/{{ auth()->user()->id }}"><i class="icofont icofont-pencil-alt-5"></i></a>
                                @endif

	                        </div>
	                        <div class="user-designation">
	                            <div class="title">
	                                <p>
	                                    <h4>{{$user->name}}</h4>

                                        @if (isset($user->title) && !empty($user->title))
                                            <h6>{{$user->title}}</h6>
                                        @endif
                                    </p>
	                            </div>
	                            <div class="social-media">
	                                <ul class="user-list-social">
                                        <li>
                                            <a href="mailto:{{$user->email}}"><i class="fa fa-envelope"></i></a>
                                        </li>
                                        @if (isset($user->link_twitter) && !empty($user->link_twitter))
                                            <li>
                                                <a href="{{$user->link_twitter}}"><i class="fa fa-twitter"></i></a>
                                            </li>
                                        @endif
                                        @if (isset($user->link_facebook) && !empty($user->link_facebook))
                                            <li>
                                                <a href="{{$user->link_facebook}}"><i class="fa fa-facebook"></i></a>
                                            </li>
                                        @endif
                                        @if (isset($user->link_instagram) && !empty($user->link_instagram))
                                            <li>
                                                <a href="{{$user->link_instagram}}"><i class="fa fa-instagram"></i></a>
                                            </li>
                                        @endif
                                        @if (isset($user->link_linkedin) && !empty($user->link_linkedin))
                                            <li>
                                                <a href="{{$user->link_linkedin}}"><i class="fa fa-linkedin"></i></a>
                                            </li>
                                        @endif
	                                </ul>
	                            </div>

                                <div class="description">
	                                <p>
                                        @if (isset($user->description) && !empty($user->description))
                                            <h6>"<span class="fst-italic">{{$user->description}}</span>"</h6>
                                        @endif
                                    </p>
	                            </div>
	                            <div class="follow">
	                                <ul class="follow-list">
	                                    <li>
	                                        <div class="follow-num counter">{{$countKanban}}</div>
	                                        <span>Kanban</span>
	                                    </li>
	                                    <li>
	                                        <div class="follow-num counter">{{$countCollaborative}}</div>
	                                        <span>Collaborative</span>
	                                    </li>
	                                    <li>
	                                        <div class="follow-num counter">{{$countItemsOwner}}</div>
	                                        <span>Task</span>
	                                    </li>
	                                </ul>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <!-- user profile header end-->
            </div>
        </div>
    </div>
    @push('scripts')
	<script src="{{asset('assets/js/counter/jquery.waypoints.min.js')}}"></script>
    <script src="{{asset('assets/js/counter/jquery.counterup.min.js')}}"></script>
    <script src="{{asset('assets/js/counter/counter-custom.js')}}"></script>
	@endpush

@endsection

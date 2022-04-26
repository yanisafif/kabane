@extends('layout.master')

@section('title')Chat App

@endsection

@push('css')
@endpush

@section('content')

	@if(!$data['kanbanNotSelected'] && !$data['kanbanNotFound'])
	<div class="container-fluid">
	    <div class="row">
	        <div class="col call-chat-body">
	            <div class="card">
	                <div class="card-body p-0">
	                    <div class="row chat-box">
	                        <!-- Chat right side start-->
	                        <div class="col chat-right-aside">
	                            <!-- chat start-->
	                            <div class="chat">
	                                <div class="mb-0 chat-history chat-msg-box custom-scrollbar">
										<ul>
											@foreach($data['messages'] as $message)
												<li class="clearfix">
													<div class="message {{ $message['isCurrentUser'] ? 'my-message' : 'other-message pull-right' }}">
														<div class="message-data">
															@if(is_null($message['path_image']))
																<img class="rounded-circle chat-user-img img-30 mr-2" style="vertical-align: bottom" src="{{asset('/assets/images/dashboard/1.png')}}" />
															@else
																<img class="rounded-circle chat-user-img img-30 mr-2" style="vertical-align: bottom; height: 30px" src="{{asset('/avatars/' . $message['path_image'] )}}" />
																{{-- <img src="{{asset('/assets/avatars/' . $person['path_image'] )}}" style="height: 20px; width: 20px" class="rounded-circle"> --}}
															@endif
															<strong style="margin-right: 10px"> {{ $message['username'] }} </strong>
															<i class="message-data-time "> 
																{{ date('j F, Y H:i:s', strtotime($message['created_at'])) }} 
															</i>
														</div>
														{{ $message['content']}}
													</div>
												</li>
											@endforeach
	                                    </ul>
	                                </div>
	                                <!-- end chat-history-->
	                            </div>
								<div class="chat-message clearfix">
									<div class="row">
										<div class="d-flex" style="height: 50px">
											<div class="input-group text-box">
												<input class="form-control input-txt-bx" id="message-to-send" type="text" name="message-to-send" placeholder="Type a message......" />
												<button class="btn btn-primary input-group-text" type="button">SEND</button>
											</div>
										</div>
									</div>
								</div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
    @elseif($data['kanbanNotSelected'])
        <div class="text-center">
            <div class="row justify-content-center">
                <h1>Welcome back to Kabane</h1>
                <h3>Select your favorite Kanban or create a brand new one.</h3>
                <div class="col-sm-6 align-items-center">
                   <figure>
                    <img class="img-fluid" src=" {{ asset('assets/svg/main.svg') }}">
                   </figure>
                </div>
            </div>
        </div>
    @elseif($data['kanbanNotFound'])
        <div class="text-center mt-5">
            <h1>Sorry, this kanban couldn't be found. </h1>
        </div>
    @endif


	@push('scripts')
	<script src="{{asset('assets/js/fullscreen.js')}}"></script>
	@endpush

@endsection

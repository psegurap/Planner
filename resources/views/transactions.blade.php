@extends('layouts.main')
{{-- {{dd(session()->all())}} --}}
@section('title') Transactions @endsection
@section('styles')
    <link href="{{asset('cork/plugins/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('cork/assets/css/apps/contacts.css')}}" rel="stylesheet" type="text/css" />

    <style>
        .searchable-items .items .item-content .amount-less p{
            color: red;
            font-weight: bold;
        }

        .searchable-items .items .item-content .amount-plus p{
            color: #04b104;
            font-weight: bold;
        }

        .searchable-items .date-container .date{
            font-size: large;
            font-weight: bold;
        }
    </style>
@endsection
@section('content')
    <!--  BEGIN CONTENT PART  -->
    <div class="layout-px-spacing">
        <div class="page-header">
            <nav class="breadcrumb-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Planner</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="javascript:void(0);">Transactions</a></li>
                </ol>
            </nav>
        </div>                
        <div class="row layout-spacing layout-top-spacing" id="cancel-row">
            <div class="col-lg-12">
                <div class="widget-content searchable-container list">

                    <div class="row" style="flex-wrap: nowrap;">
                        <div class="col-md-12" style="margin-bottom: 1em;">
                            <div class="">
                                <svg id="btn-add-contact" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                            </div>

                        </div>

                        <div class="col-xl-8 col-lg-7 col-md-7 col-sm-5 text-sm-right text-center layout-spacing align-self-center">

                            <!-- Modal -->
                            <div class="modal fade" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="addContactModalTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <i class="flaticon-cancel-12 close" data-dismiss="modal"></i>
                                            <div class="add-contact-box">
                                                <div class="add-contact-content">
                                                    <form id="addContactModalTitle">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="contact-name">
                                                                    <i class="flaticon-user-11"></i>
                                                                    <input type="text" id="c-name" class="form-control" placeholder="Name">
                                                                    <span class="validation-text"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="contact-email">
                                                                    <i class="flaticon-mail-26"></i>
                                                                    <input type="text" id="c-email" class="form-control" placeholder="Email">
                                                                    <span class="validation-text"></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="contact-occupation">
                                                                    <i class="flaticon-fill-area"></i>
                                                                    <input type="text" id="c-occupation" class="form-control" placeholder="Occupation">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="contact-phone">
                                                                    <i class="flaticon-telephone"></i>
                                                                    <input type="text" id="c-phone" class="form-control" placeholder="Phone">
                                                                    <span class="validation-text"></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="contact-location">
                                                                    <i class="flaticon-location-1"></i>
                                                                    <input type="text" id="c-location" class="form-control" placeholder="Location">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button id="btn-edit" class="float-left btn">Save</button>

                                            <button class="btn" data-dismiss="modal"> <i class="flaticon-delete-1"></i> Discard</button>

                                            <button id="btn-add" class="btn">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="searchable-items list">
                        {{-- <div class="items items-header-section">
                            <div class="item-content">
                                <div class="">
                                    <div class="n-chk align-self-center text-center">
                                        <label class="new-control new-checkbox checkbox-primary">
                                          <input type="checkbox" class="new-control-input" id="contact-check-all">
                                          <span class="new-control-indicator"></span>
                                        </label>
                                    </div>
                                    <h4>Name</h4>
                                </div>
                                <div class="user-email">
                                    <h4>Email</h4>
                                </div>
                                <div class="user-location">
                                    <h4 style="margin-left: 0;">Location</h4>
                                </div>
                                <div class="user-phone">
                                    <h4 style="margin-left: 3px;">Phone</h4>
                                </div>
                                <div class="action-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2  delete-multiple"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                </div>
                            </div>
                        </div> --}}
                        <div class="date-container">
                            <p class="date">June 27</p>
                        </div>
                        <div class="items">
                            <div class="item-content">
                               <div>
                                    <div class="user-location amount-less">
                                        <p >-962.99</p>
                                    </div>
                                    <div class="user-location">
                                        <p>Assumenda iusto dolorem vero.</p>
                                    </div>
                               </div>
                                <div class="action-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 edit"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                </div>
                            </div>
                        </div>
                        <div class="items">
                            <div class="item-content">
                                <div>
                                    <div class="user-location amount-less">
                                        <p >-953.62</p>
                                    </div>
                                    <div class="user-location">
                                        <p>Assumenda iusto dolorem vero.</p>
                                    </div>
                                </div>
                                <div class="action-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 edit"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                </div>
                            </div>
                        </div>
                        <div class="date-container">
                            <p class="date">June 22</p>
                        </div>
                        <div class="items">
                            <div class="item-content">
                                <div>
                                    <div class="user-location amount-less">
                                        <p >-381.9</p>
                                    </div>
                                    <div class="user-location">
                                        <p>Assumenda iusto dolorem vero.</p>
                                    </div>
                                </div>
                                <div class="action-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 edit"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                </div>
                            </div>
                        </div>
                        <div class="date-container">
                            <p class="date">June 20</p>
                        </div>
                        <div class="items">
                            <div class="item-content">
                                <div>
                                    <div class="user-location amount-plus">
                                        <p >802.94</p>
                                    </div>
                                    <div class="user-location">
                                        <p>Assumenda iusto dolorem vero.</p>
                                    </div>
                                </div>
                                <div class="action-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 edit"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                </div>
                            </div>
                        </div>
                        <div class="items">
                            <div class="item-content">
                                <div>
                                    <div class="user-location amount-less">
                                        <p >-617.98</p>
                                    </div>
                                    <div class="user-location">
                                        <p>Assumenda iusto dolorem vero.</p>
                                    </div>
                                </div>
                                <div class="action-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 edit"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                </div>
                            </div>
                        </div>
                        <div class="items">
                            <div class="item-content">
                                <div>
                                    <div class="user-location amount-less">
                                        <p >-700.3</p>
                                    </div>
                                    <div class="user-location">
                                        <p>Assumenda iusto dolorem vero.</p>
                                    </div>
                                </div>
                                <div class="action-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 edit"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--  END CONTENT PART  -->
@endsection

@section('scripts')
    
@endsection
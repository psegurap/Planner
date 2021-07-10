@extends('layouts.main')
{{-- {{dd(session()->all())}} --}}
@section('title') Expenses @endsection

@section('styles')
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{asset('/cork/assets/css/apps/notes.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/cork/assets/css/forms/theme-checkbox-radio.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/cork/assets/css/components/custom-list-group.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/cork/plugins/sweetalerts/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/cork/plugins/sweetalerts/sweetalert.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/cork/assets/css/components/custom-sweetalert.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/cork/plugins/flatpickr/flatpickr.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/cork/plugins/flatpickr/custom-flatpickr.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('cork/plugins/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('cork/assets/css/apps/contacts.css')}}" rel="stylesheet" type="text/css" />

    <!-- END PAGE LEVEL STYLES -->

    <style>
        #expense-form{
            max-width: 100%;
            flex: 0 0 100%;
        }

        .expense-title {
            font-size: 16px;
            font-weight: 500;
            color: #61b6cd;
            margin-bottom: 0px;
            letter-spacing: 0px;
        }

        .searchable-items .items .item-content .amount-less span.amount{
            display: block;
            color: red;
            font-weight: bold;
        }

        .searchable-items .date-container .date{
            font-size: large;
            font-weight: bold;
        }

        .searchable-container .searchable-items.list .items .item-content{
            min-width: 100%
        }

        @media (max-width: 767px){
            .searchable-container .searchable-items.list .items{
                min-width: 100%;
            }
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
                    <li class="breadcrumb-item active" aria-current="page"><a href="javascript:void(0);">Expenses</a></li>
                </ol>
            </nav>
        </div>
        
        <div class="row app-notes layout-top-spacing" id="cancel-row">
            <div class="col-lg-12">
                <div class="app-container">
                    <div class="app-note-container">
                        <div class="note-container  note-grid">
                            <div class="note-item all-notes note-personal" id="expense-form">
                                <div class="note-inner-content rounded border-danger">
                                    <div class="row mb-2" >
                                        <div class="col-12">
                                            <p class="d-flex justify-content-between mb-3 note-title">
                                                <span class="font-weight-bold text-uppercase">Add a new expense</span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input data-vv-scope="add-expense" name="name" v-model="new_expense.name" :disabled="sending" v-validate="'required|max:25'" type="text" placeholder="Name:" class="form-control text-white">
                                                <span class="text-danger" style="font-size: 12px;" v-show="errors.has('add-expense.name')">* @{{ errors.first('add-expense.name') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                      <span class="input-group-text">$</span>
                                                    </div>
                                                    <input data-vv-scope="add-expense" name="expected amount" type="text" :disabled="sending" v-validate="'required|decimal:2|max:15'" v-model="new_expense.amount_expected" placeholder="Amount expected:" class="form-control text-white" aria-label="Amount (to the nearest dollar)">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">.00</span>
                                                    </div>
                                                </div>
                                                <span class="text-danger" style="font-size: 12px;" v-show="errors.has('add-expense.expected amount')">* @{{ errors.first('add-expense.expected amount') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea data-vv-scope="add-expense" v-validate="'max:150'" :disabled="sending" name="description" style="max-height: 200px;" v-model="new_expense.description " placeholder="Description (optional):" name="" id="" cols="30" rows="5" class="form-control text-white"></textarea>
                                                <span class="text-danger" style="font-size: 12px;" v-show="errors.has('add-expense.description')">* @{{ errors.first('add-expense.description') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-between">
                                                <button v-on:click="validate(AddExpense, 'add-expense')" class="btn btn-lg btn-primary mb-2"> Add Expense</button>
                                                <span v-if="sending" class="spinner-border text-light align-self-center loader-sm"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="border border-white">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="app-container">
                    <div class="app-note-container">
                        <div class="app-note-overlay"></div>
                        <div id="ct" class="note-container note-grid">
                            <div v-for="expense in expenses" class="note-item all-notes note-personal">
                                <div class="border-danger note-inner-content">
                                    <div class="note-content">
                                        <p class="note-title" data-noteTitle="Meeting with Kelly">@{{expense.name}}</p>
                                        <div class="mt-2">
                                            <ul class="list-group">
                                                <li class="list-group-item text-white mb-1">Expected: $@{{expense.expected_amount}}</li>
                                                <li class="list-group-item text-white mb-1">Current: $@{{expense.current_addition}}</li>
                                                <li class="list-group-item text-white">Difference: 
                                                    <span v-if="expense.difference > 0" class="text-success" >$@{{expense.difference}}</span>
                                                    <span v-else-if="expense.difference < 0" class="text-danger">$@{{expense.difference}}</span>
                                                    <span v-else>$@{{expense.difference}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div class="note-action">
                                            <span @click="EditModal(expense.id)" class="badge link-badge-warning">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </span>
                                            <span @click="DeleteExpense(expense.id)" class="badge link-badge-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                            </span>
                                        </div>
                                        <div class="note-action">
                                            <span @click="AddTransaction(expense.id)" class="badge link-badge-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <!-- EDIT EXPENSE -->
                <div class="modal fade" id="expenseModal" tabindex="-1" role="dialog" aria-labelledby="notesMailModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body pb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="modal"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                <div class="notes-box">
                                    <div class="notes-content">                                                                        
                                        <form action="javascript:void(0);" id="notesMailModalTitle" data-vv-scope="edit-expense">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <input :disabled="sending" id="n-title" name="name" v-model="edit_expense.name" v-validate="'required|max:25'" type="text" placeholder="Name:" class="form-control text-white">
                                                        <span class="text-danger" style="font-size: 12px;" v-show="errors.has('edit-expense.name')">* @{{ errors.first('edit-expense.name') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                              <span class="input-group-text">$</span>
                                                            </div>
                                                            <input :disabled="sending" id="n-amount-expected" name="expected amount" type="text" v-validate="'required|decimal:2|max:15'" v-model="edit_expense.amount_expected" placeholder="Amount expected:" class="form-control text-white" aria-label="Amount (to the nearest dollar)">
                                                        </div>
                                                        <span class="text-danger" style="font-size: 12px;" v-show="errors.has('edit-expense.expected amount')">* @{{ errors.first('edit-expense.expected amount') }}</span>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <textarea :disabled="sending" v-validate="'max:150'" name="description" style="max-height: 200px;" v-model="edit_expense.description " placeholder="Description:" cols="30" rows="5" class="form-control text-white"></textarea>
                                                        <span class="text-danger" style="font-size: 12px;" v-show="errors.has('edit-expense.description')">* @{{ errors.first('edit-expense.description') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <span v-if="sending" class="spinner-border text-light align-self-center loader-sm"></span>
                                <button id="btn-n-save" @click="validate(UpdateExpense, 'edit-expense')" class="float-left btn">Update</button>
                                <button class="btn" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ADD TRANSACTION -->
                <div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="notesMailModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-body pb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="modal"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                <div class="notes-box">
                                    <div class="notes-content">                                                                        
                                        <form action="javascript:void(0);"  data-vv-scope="add-transaction">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div>
                                                        <p class="expense-title text-uppercase">@{{transaction.expense_name}}</p>
                                                    </div>
                                                    <hr class="border border-danger mt-3">
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input v-model="transaction.date" name="date" v-validate="'required'" id="transactionDate" class="form-control flatpickr active text-white" type="text" placeholder="Select Date:">
                                                        <span class="text-danger" style="font-size: 12px;" v-show="errors.has('add-transaction.date')">* @{{ errors.first('add-transaction.date') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                              <span class="input-group-text">$</span>
                                                            </div>
                                                            <input v-model="transaction.amount" :disabled="sending" name="amount" type="text" v-validate="'required|decimal:2|max:15'" placeholder="Amount:" class="form-control text-white" aria-label="Amount (to the nearest dollar)">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">.00</span>
                                                            </div>
                                                        </div>
                                                        <span class="text-danger" style="font-size: 12px;" v-show="errors.has('add-transaction.amount')">* @{{ errors.first('add-transaction.amount') }}</span>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <textarea v-model="transaction.description" :disabled="sending" v-validate="'max:150'" name="description" style="max-height: 200px;" placeholder="Description:" cols="30" rows="5" class="form-control text-white"></textarea>
                                                        <span class="text-danger" style="font-size: 12px;" v-show="errors.has('add-transaction.description')">* @{{ errors.first('add-transaction.description') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <span v-if="sending" class="spinner-border text-light align-self-center loader-sm"></span>
                                <button id="btn-n-save" @click="validate(SaveTransaction, 'add-transaction')" class="float-left btn">Add Transaction</button>
                                <button class="btn" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!------- Transactions List --------->
            <div class="col-lg-12">
                <div class="widget-content searchable-container list">
                    <div class="searchable-items list">
                        <div v-for="(transaction, key) in transactions" class="loop-container">
                            <div class="date-container">
                                <p class="date">@{{moment(key).format("MMM Do")}}</p>
                            </div>
                            <div v-for="single_transaction in transaction" class="items">
                                <div class="item-content">
                                    <div>
                                        <div class="user-location amount-less">
                                            <span class="amount">@{{single_transaction.amount}}</span>
                                            <span class="text-white"> - @{{single_transaction.expense.name}}</span>
                                        </div>
                                        <div class="user-location">
                                            <span>@{{single_transaction.description}}</span>
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
    </div>
        
    <!--  END CONTENT PART  -->
@endsection

@section('scripts')
    <script src="{{asset('/cork/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('/cork/assets/js/app.js')}}"></script>
    <script src="{{asset('/cork/plugins/sweetalerts/sweetalert2.min.js')}}"></script>
    <script src="{{asset('/cork/plugins/sweetalerts/custom-sweetalert.js')}}"></script>

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{asset('/cork/assets/js/ie11fix/fn.fix-padStart.js')}}"></script>
    <script src="{{asset('/cork/assets/js/apps/notes.js')}}"></script>
    <script src="{{asset('/cork/plugins/flatpickr/flatpickr.js')}}"></script>
    <script src="{{asset('/cork/plugins/flatpickr/flatpickr.js')}}"></script>
    <!-- END PAGE LEVEL SCRIPTS -->

    <script>
        var expenses = {!! json_encode($expenses) !!};
        var transactions = {!! json_encode($transactions) !!};

    </script>
    <!-- Begin Custom Files -->
    <script src="{{asset('/js/custom/expenses.js')}}"></script>
        
    <!-- End Custom Files -->
@endsection
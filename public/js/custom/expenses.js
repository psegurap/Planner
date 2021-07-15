$(document).ready(function(){
    Vue.use(VeeValidate);

    const toast = swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        padding: '2em'
    });

    expenses = new Vue({
        el: '#content',
        data : {
            expenses : expenses,
            transactions: transactions,
            sending : false,
            moment : moment,
            new_expense : {
                name : '',
                amount_expected : '',
                description : ''
            },
            editing_expense_id : null,
            edit_expense : {
                name : '',
                amount_expected : '',
                description : ''
            },
            transaction : {
                date : "",
                amount : "",
                description : "",
                expense_name : ""
            },
            transaction_expense_id : null,  
        },
        watch: {
            EditingExpenseId: function(val){
                let expense = val[0];
                if(typeof expense !== 'undefined'){
                    this.edit_expense.name = expense.name;
                    this.edit_expense.amount_expected = expense.expected_amount;
                    this.edit_expense.description = expense.description
                }
            },
            TransactionExpenseId: function(val){
                let expense = val[0];
                if(typeof expense !== 'undefined'){
                    this.transaction.expense_name = expense.name;
                }

            }
        },
        computed : {
            EditingExpenseId: function(){
                var _this = this;
                return this.expenses.filter(function(expense){
                    return expense.id == _this.editing_expense_id;
                });
            },
            TransactionExpenseId: function(){
                var _this = this;
                return this.expenses.filter(function(expense){
                    return expense.id == _this.transaction_expense_id;
                });
            }
        },
        mounted: function(){
            console.log("<-- Ready -->");
            var datePicker = flatpickr(document.getElementById('transactionDate'), {
                dateFormat: "Y-m-d"
            });
        },
        methods:{
            //BEGIN EXPENSE FUNCTIONS 
            AddExpense: function(){
                var _this = this;
                this.sending = true;
                axios.post(homepath + '/expenses/add_expense', {expense_info : this.new_expense}).then(function(response){
                    _this.expenses = response.data;
                    _this.sending = false;
                    _this.new_expense.name = '';
                    _this.new_expense.amount_expected = '';
                    _this.new_expense.description = '';
                    swal({
                        title: 'Success!',
                        text: "Expense successfully added!",
                        type: 'success',
                        padding: '2em',
                    }).then(function(){
                        _this.errors.clear();
                        // window.location.reload();
                    });
                }).catch(function(error){
                    _this.sending = false;
                    toast({
                        type: 'error',
                        title: 'An error has occurred.',
                        padding: '2em',
                    })
                    console.log(error);
                })
            },
            EditModal:  function(expense_id){
                var _this = this;
                this.editing_expense_id = expense_id;
                $('#expenseModal').modal('show');
            },
            UpdateExpense: function(){
                var _this = this;
                this.sending = true;
                axios.post(homepath + '/expenses/update_expense', {expense_id : this.editing_expense_id, expense_info : this.edit_expense}).then(function(response){
                    _this.expenses = response.data;
                    _this.sending = false;
                    _this.edit_expense.name = '';
                    _this.edit_expense.amount_expected = '';
                    _this.edit_expense.description = '';
                    $('#expenseModal').modal('hide');
                    toast({
                        type: 'success',
                        title: 'Expense successfully updated.',
                        padding: '2em',
                    })
                    _this.errors.clear();
                }).catch(function(error){
                    _this.sending = false;
                    toast({
                        type: 'error',
                        title: 'An error has occurred.',
                        padding: '2em',
                    })
                    console.log(error);
                })
            },
            DeleteExpense: function(expense_id){
                var _this = this;
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    padding: '2em'
                  }).then(function(result) {
                    var _this_ = _this;
                    if (result.value) {
                        axios.post(homepath + '/expenses/delete_expense/' + expense_id).then(function(response){
                            _this_.expenses = response.data;
                            swal(
                                'Deleted!',
                                'Expense successfully removed.',
                                'success'
                            )
                        }).catch(function(error){
                            _this.sending = false;
                            toast({
                                type: 'error',
                                title: 'An error has occurred.',
                                padding: '2em',
                            })
                            console.log(error);
                        })
                      
                    }
                  })
            },
            // -------------- END EXPENSE FUNCTIONS 

            // -------------- BEGIN TRANSACTION FUNCTIONS 
            AddTransaction: function(id){
                $('#transactionModal').modal('show');
                this.transaction_expense_id = id;
            },
            SaveTransaction: function(){
                var _this = this;
                this.sending = true;
                axios.post(homepath + '/expenses/transaction/add', {expense_id : this.transaction_expense_id, transaction : this.transaction}).then(function(response){
                    _this.expenses = response.data.expenses;
                    _this.transactions = response.data.transactions;
                    _this.sending = false;
                    _this.transaction.amount = '';
                    _this.transaction.description = '';
                    swal({
                        title: 'Success!',
                        text: "Transaction successfully added!",
                        type: 'success',
                        padding: '2em',
                    }).then(function(){
                        _this.errors.clear();
                        // window.location.reload();
                    });
                }).catch(function(error){
                    _this.sending = false;
                    toast({
                        type: 'error',
                        title: 'An error has occurred.',
                        padding: '2em',
                    })
                    console.log(error);
                })
            },
            RemoveTransaction: function(transaction_id){
                var _this = this;
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    padding: '2em'
                  }).then(function(result) {
                    var _this_ = _this;
                    if (result.value) {
                        axios.post(homepath + '/expenses/transaction/delete_transaction/' + transaction_id).then(function(response){
                            _this.expenses = response.data.expenses;
                            _this.transactions = response.data.transactions;
                            swal(
                                'Deleted!',
                                'Transaction successfully deleted.',
                                'success'
                            )
                        }).catch(function(error){
                            _this.sending = false;
                            toast({
                                type: 'error',
                                title: 'An error has occurred.',
                                padding: '2em',
                            })
                            console.log(error);
                        })
                      
                    }
                });
            },
            // -------------- END TRANSACTION FUNCTIONS 

            validate: function(callback, scope){
                var _this = this;
                this.$validator.validateAll(scope).then(function(result){
                    if(result){
                        callback();
                    }else{
                        toast({
                            type: 'error',
                            title: 'Please, complete all required fields.',
                            padding: '2em',
                        })
                        // $.toast({
                        //     heading: 'Error',
                        //     text: 'Please, complete all required fields.',
                        //     showHideTransition: 'fade',
                        //     icon: 'error',
                        //     position : 'top-right'
                        // })
                    }
                })
            }
        }
    });

});
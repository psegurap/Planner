$(document).ready(function(){
    Vue.use(VeeValidate);

    const toast = swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        padding: '2em'
    });

    transasctions = new Vue({
        el: '#content',
        data : {
            transactions: transactions,
            moment : moment,
            transaction : {
                date : "",
                amount : "",
                description : "",
                income_name : ""
            },
            transaction_income_id : null,  
        },
        watch: {
            // TransactionIncomeId: function(val){
            //     let income = val[0];
            //     if(typeof income !== 'undefined'){
            //         this.transaction.income_name = income.name;
            //     }

            // }
        },
        computed : {
            // TransactionIncomeId: function(){
            //     var _this = this;
            //     return this.incomes.filter(function(income){
            //         return income.id == _this.transaction_income_id;
            //     });
            // }
        },
        mounted: function(){
            console.log("<-- Ready -->");
        },
        methods:{
            // -------------- BEGIN TRANSACTION FUNCTIONS 
            // AddTransaction: function(id){
            //     $('#transactionModal').modal('show');
            //     this.transaction_income_id = id;
            // },
            // SaveTransaction: function(){
            //     var _this = this;
            //     this.sending = true;
            //     axios.post(homepath + '/incomes/transaction/add', {income_id : this.transaction_income_id, transaction : this.transaction}).then(function(response){
            //         _this.incomes = response.data.incomes;
            //         _this.transactions = response.data.transactions;
            //         _this.sending = false;
            //         _this.transaction.amount = '';
            //         _this.transaction.description = '';
            //         swal({
            //             title: 'Success!',
            //             text: "Transaction successfully added!",
            //             type: 'success',
            //             padding: '2em',
            //         }).then(function(){
            //             _this.errors.clear();
            //             // window.location.reload();
            //         });
            //     }).catch(function(error){
            //         _this.sending = false;
            //         toast({
            //             type: 'error',
            //             title: 'An error has occurred.',
            //             padding: '2em',
            //         })
            //         console.log(error);
            //     })
            // },
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
                        axios.post(homepath + '/incomes/transaction/delete_transaction/' + transaction_id).then(function(response){
                            _this.incomes = response.data.incomes;
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
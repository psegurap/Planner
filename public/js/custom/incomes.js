$(document).ready(function(){
    Vue.use(VeeValidate);

    const toast = swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        padding: '2em'
    });

    incomes = new Vue({
        el: '#content',
        data : {
            incomes : incomes,
            sending : false,
            new_income : {
                name : '',
                amount_expected : '',
                description : ''
            },
            editing_income_id : null,
            edit_income : {
                name : '',
                amount_expected : '',
                description : ''
            }
        },
        watch: {
            EditingIncomeId: function(val){
                let income = val[0]
                this.edit_income.name = income.name;
                this.edit_income.amount_expected = income.expected_amount;
                this.edit_income.description = income.description
                // console.log(val)
            }
        },
        computed : {
            EditingIncomeId: function(){
                var _this = this;
                return this.incomes.filter(function(income){
                    return income.id == _this.editing_income_id;
                });
            }
        },
        mounted: function(){
            console.log("<-- Ready -->");

            
            // console.log(incomes)
        },
        methods:{
            AddIncome: function(){
                var _this = this;
                this.sending = true;
                axios.post(homepath + '/incomes/add_income', {income_info : this.new_income}).then(function(response){
                    _this.incomes = response.data;
                    _this.sending = false;
                    _this.new_income.name = '';
                    _this.new_income.amount_expected = '';
                    _this.new_income.description = '';
                    swal({
                        title: 'Success!',
                        text: "Income successfully added!",
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
            EditModal:  function(income_id){
                var _this = this;
                this.editing_income_id = income_id;
                $('#incomeModal').modal('show');
            },
            UpdateIncome: function(){
                var _this = this;
                this.sending = true;
                axios.post(homepath + '/incomes/update_income', {income_id : this.editing_income_id, income_info : this.edit_income}).then(function(response){
                    _this.incomes = response.data;
                    _this.sending = false;
                    _this.edit_income.name = '';
                    _this.edit_income.amount_expected = '';
                    _this.edit_income.description = '';
                    $('#incomeModal').modal('hide');
                    toast({
                        type: 'success',
                        title: 'Income successfully updated.',
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
            DeleteIncome: function(income_id){
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
                        axios.post(homepath + '/incomes/delete_income/' + income_id).then(function(response){
                            _this_.incomes = response.data;
                            swal(
                                'Deleted!',
                                'Income successfully removed.',
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
            validate: function(callback, scope){
                console.log(callback, scope)
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
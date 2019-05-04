<template>
    <div class="container m-3">
        <div class="row">
            <div class="col-md-6">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCenter">
                    Add New
                </button>

                <!-- Modal -->
                <div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalLongTitle">Add New City</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="addCity">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="Name" v-model="city.name" v-validate="'required|max:50|min:2'">
                                        <div class="help-block alert alert-danger" v-show="errors.has('name')">
                                           {{errors.first('name')}}
                                        </div>
                                        <br>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="arabic-name" class="form-control" placeholder="Arabic Name" v-model="city.arabic_name" v-validate="'required|max:50|min:2'">
                                        <div class="help-block alert alert-danger" v-show="errors.has('arabic_name')">
                                           {{errors.first('arabic_name')}}
                                        </div>
                                        <br>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" name="name" class="form-control" placeholder="Order" v-model="city.order" v-validate="'required'">
                                        <div class="help-block alert alert-danger" v-show="errors.has('order')">
                                           {{errors.first('order')}}
                                        </div>
                                        <div class="help-block alert alert-danger" v-show="existErr('order')">
                                           {{getErr('order')}}
                                        </div>
                                        <br>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button class="btn btn-primary" type="submit" v-show="city.name && city.arabic_name">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
        name:'AddCityComponentVue',
        data(){
            return{
            city:{
                id:'',
                name:'',
                arabic_name:'',
                order: 0
            },
            errs:[]
            }
        },
        methods:{
            addCity(){
                //const isValid = await this.$refs.observer.validate();
                debugger;
                if(!this._validate()){
                    this.errs = [];
                    fetch('city',{
                        method:'post',
                        body: JSON.stringify(this.city),
                        headers:{
                            'content-type' : 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).then(res=>res.json())
                    .then(res=>{
                        if(res.error != null){
                            var _err = {
                                name : res.error,
                                msg : res.msg
                            }
                            this.errs.push(_err);
                        }
                        else{
                            this.city.name='';
                            this.city.arabic_name='';
                            this.city.order='0';
                            $('#ModalCenter').modal('hide')
                        }
                        
                    })
                    .catch(err=>console.log('error', err));
                }
            },
            _validate(){
                this.$validator.validateAll();
                return this.errors.any();
            },
            existErr(err_name){
                var isExist = false;
                this.errs.forEach(err => {
                    if(err.name == err_name)
                        isExist = true;
                });
                return isExist;
            },
            getErr(err_name){
                var msg = '';
                this.errs.forEach(err => {
                    if(err.name == err_name)
                        msg = err.msg;
                });
                return msg;
            }
        }
                     
    }
</script>
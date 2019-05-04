<template>
    <div id="app" class="container">
        <div class="container m-3">
        <div class="row">
            <div class="col-md-6">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" @click="showPopup">
                    Add New
                </button>

                <!-- Modal -->
                <div class="modal fade" id="InsuranceModalCenter" tabindex="-1" role="dialog" aria-labelledby="InsuranceModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalLongTitle">Add New Insurance</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="updateInsurance">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="Name" v-model="insurance.name" v-validate="'required|max:50|min:2'">
                                        <div class="help-block alert alert-danger" v-show="errors.has('name')">
                                           {{errors.first('name')}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="text" name="arabic_name" class="form-control" placeholder="Arabic Name" v-model="insurance.arabic_name" v-validate="'required|max:50|min:2'">
                                        <div class="help-block alert alert-danger" v-show="errors.has('arabic_name')">
                                           {{errors.first('arabic_name')}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="number" name="order" class="form-control" placeholder="Order" v-model="insurance.order" v-validate="'required'">
                                        <div class="help-block alert alert-danger" v-show="errors.has('order')">
                                           {{errors.first('order')}}
                                        </div>
                                        <div class="help-block alert alert-danger" v-show="existErr('order')">
                                           {{getErr('order')}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="checkbox" name="national" class="form-control" placeholder="National" v-model="insurance.national">
                                    </div>
                                    <br>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button class="btn btn-primary" type="submit" v-show="insurance.name && insurance.arabic_name">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <GridComponent :columns="columns" :readUrl="url" @doubleClick="showRow" @editObject="editRow"></GridComponent>
    </div>
</template>
<script>
    import GridComponent from './../../GridData/GridComponent.vue';
    export default {
        data(){
            return{
                url:'/insurance/index',
                columns:[
                    {
                        name:'name',
                        title:'Name',
                        type:'text',
                        number:false,
                        isDate : false,
                        isTime : false,
                        isBoolean : false,
                        isReference : false,
                        isImage:false,
                        isEnum:false
                    },
                    {
                        name:'arabic_name',
                        title:'Arabic Name',
                        type:'text',
                        number:false,
                        isDate : false,
                        isTime : false,
                        isBoolean : false,
                        isReference : false,
                        isImage:false,
                        isEnum:false
                    },
                    {
                        name:'order',
                        title:'Order',
                        type:'number',
                        number:true,
                        isDate : false,
                        isTime : false,
                        isBoolean : false,
                        isReference : false,
                        isImage:false,
                        isEnum:false
                    },
                    {
                        name: 'national',
                        title:'National',
                        type:'boolean',
                        number: false,
                        isDate : false,
                        isTime : false,
                        isBoolean : true,
                        isReference : false,
                        isImage:false,
                        isEnum:false
                    }
                ],
                insurance:{
                id:'',
                name:'',
                arabic_name:'',
                order: 0,
                national: ''
                },
                errs:[],
                edit:false
            }
        },
        methods:{
            updateInsurance(){
                if(!this._validate()){
                    this.errs = [];
                    if(this.edit === false){
                        fetch('insurance',{
                            method:'post',
                            body: JSON.stringify(this.insurance),
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
                                this.insurance.name='';
                                this.insurance.arabic_name='';
                                this.insurance.order='0';
                                this.insurance.national= false;
                                $('#InsuranceModalCenter').modal('hide');
                                this.refreshData();
                            }
                        })
                        .catch(err=>console.log('error', err));
                    }
                    else{
                        let _id= this.insurance.id;
                        fetch(`insurance/${_id}`,{
                            method:'put',
                            body: JSON.stringify(this.insurance),
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
                                this.insurance.name='';
                                this.insurance.arabic_name='';
                                this.insurance.order='0';
                                this.insurance.national= false;
                                $('#InsuranceModalCenter').modal('hide');
                                this.refreshData();
                            }
                        })
                        .catch(err=>console.log('error', err));
                    }
                }
            },
            refreshData(){
                this.$root.$emit('refreshDataGrid');
            },
            showRow(id, obj){
                console.log('obj', obj);
            },
            showPopup(){
                this.edit = false;
                this.insurance.id = 0;
                this.insurance.name='';
                this.insurance.arabic_name='';
                this.insurance.order='0';
                this.insurance.national= false;
                $('#InsuranceModalCenter').modal('show');
            },
            editRow(obj){
                this.edit = true;
                this.insurance.id = obj.id;
                this.insurance.name = obj.name;
                this.insurance.arabic_name = obj.arabic_name;
                this.insurance.order = obj.order;
                this.insurance.national= obj.national;
                $('#InsuranceModalCenter').modal('show');
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
        },
        components:{
            GridComponent
        }
    }
</script>
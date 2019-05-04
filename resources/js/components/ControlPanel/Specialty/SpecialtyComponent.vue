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
                <div class="modal fade" id="SpecialtyModalCenter" tabindex="-1" role="dialog" aria-labelledby="SpecialtyModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalLongTitle">Add New Specialty</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="updateSpecialty">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="Name" v-model="specialty.name" v-validate="'required|max:50|min:2'">
                                        <div class="help-block alert alert-danger" v-show="errors.has('name')">
                                           {{errors.first('name')}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="text" name="arabic_name" class="form-control" placeholder="Arabic Name" v-model="specialty.arabic_name" v-validate="'required|max:50|min:2'">
                                        <div class="help-block alert alert-danger" v-show="errors.has('arabic_name')">
                                           {{errors.first('arabic_name')}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="number" name="order" class="form-control" placeholder="Order" v-model="specialty.order" v-validate="'required'">
                                        <div class="help-block alert alert-danger" v-show="errors.has('order')">
                                           {{errors.first('order')}}
                                        </div>
                                        <div class="help-block alert alert-danger" v-show="existErr('order')">
                                           {{getErr('order')}}
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button class="btn btn-primary" type="submit" v-show="specialty.name && specialty.arabic_name">Save</button>
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
                url:'/specialty/index',
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
                    }
                ],
                specialty:{
                id:'',
                name:'',
                arabic_name:'',
                order: 0
                },
                errs:[],
                edit:false
            }
        },
        methods:{
            updateSpecialty(){
                if(!this._validate()){
                    this.errs = [];
                    if(this.edit === false){
                        fetch('specialty',{
                            method:'post',
                            body: JSON.stringify(this.specialty),
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
                                this.specialty.name='';
                                this.specialty.arabic_name='';
                                this.specialty.order='0';
                                $('#SpecialtyModalCenter').modal('hide');
                                this.refreshData();
                            }
                        })
                        .catch(err=>console.log('error', err));
                    }
                    else{
                        let _id= this.specialty.id;
                        fetch(`specialty/${_id}`,{
                            method:'put',
                            body: JSON.stringify(this.specialty),
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
                                this.specialty.name='';
                                this.specialty.arabic_name='';
                                this.specialty.order='0';
                                $('#SpecialtyModalCenter').modal('hide');
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
                this.specialty.id = 0;
                this.specialty.name='';
                this.specialty.arabic_name='';
                this.specialty.order='0';
                $('#SpecialtyModalCenter').modal('show');
            },
            editRow(obj){
                this.edit = true;
                this.specialty.id = obj.id;
                this.specialty.name = obj.name;
                this.specialty.arabic_name = obj.arabic_name;
                this.specialty.order = obj.order;
                $('#SpecialtyModalCenter').modal('show');
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
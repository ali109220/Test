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
                <div class="modal fade" id="SubSpecialtyModalCenter" tabindex="-1" role="dialog" aria-labelledby="SubSpecialtyModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalLongTitle">Add New Sub Specialty</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="updateSubSpecialty">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="Name" v-model="sub_specialty.name" v-validate="'required|max:50|min:2'">
                                        <div class="help-block alert alert-danger" v-show="errors.has('name')">
                                           {{errors.first('name')}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="text" name="arabic_name" class="form-control" placeholder="Arabic Name" v-model="sub_specialty.arabic_name" v-validate="'required|max:50|min:2'">
                                        <div class="help-block alert alert-danger" v-show="errors.has('arabic_name')">
                                           {{errors.first('arabic_name')}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="number" name="order" class="form-control" placeholder="Order" v-model="sub_specialty.order" v-validate="'required'">
                                        <div class="help-block alert alert-danger" v-show="errors.has('order')">
                                           {{errors.first('order')}}
                                        </div>
                                        <div class="help-block alert alert-danger" v-show="existErr('order')">
                                           {{getErr('order')}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <b-form-select class="form-control" name="specialty_select" v-model="selected" :options="options" v-validate="'required'"/>
                                        <div class="help-block alert alert-danger" v-show="errors.has('specialty_select')">
                                           {{errors.first('specialty_select')}}
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button class="btn btn-primary" type="submit" v-show="sub_specialty.name && sub_specialty.arabic_name">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <GridComponent v-if="Shown" :columns="columns" :readUrl="url" :references="references" @doubleClick="showRow" @editObject="editRow"></GridComponent>
    </div>
</template>
<script>
    import GridComponent from './../../GridData/GridComponent.vue';
    export default {
        data(){
            return{
                references: [
                    {
                        name:'specialty_name',
                        url: '/allSpecialties'
                    }
                ],
                url:'/sub_specialty/index',
                columns:[
                    {
                        name:'name',
                        title:'Name',
                        type:'text',
                        number:false,
                        isDate : false,
                        isTime : false,
                        isBoolean : false,
                        isReference : false
                    },
                    {
                        name:'arabic_name',
                        title:'Arabic Name',
                        type:'text',
                        number:false,
                        isDate : false,
                        isTime : false,
                        isBoolean : false,
                        isReference : false
                    },
                    {
                        name:'order',
                        title:'Order',
                        type:'number',
                        number:true,
                        isDate : false,
                        isTime : false,
                        isBoolean : false,
                        isReference : false
                    },
                    {
                        name:'specialty_name',
                        title:'Specialty',
                        type:'reference',
                        number:false,
                        isDate : false,
                        isTime : false,
                        isBoolean : false,
                        isReference : true
                    }
                ],
                sub_specialty:{
                id:'',
                name:'',
                arabic_name:'',
                order: 0,
                specialty_id:''
                },
                errs:[],
                edit:false,
                selected: null,
                options: [],
                Shown: false
            }
        },
        beforeMount(){
            this.fetchSpecialtiesSelectList();
        },
        methods:{
            updateSubSpecialty(){
                this.sub_specialty.specialty_id = this.selected;
                if(!this._validate()){
                    this.errs = [];
                    if(this.edit === false){
                        fetch('sub_specialty',{
                            method:'post',
                            body: JSON.stringify(this.sub_specialty),
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
                                this.sub_specialty.name='';
                                this.sub_specialty.arabic_name='';
                                this.sub_specialty.order='0';
                                this.selected = null;
                                $('#SubSpecialtyModalCenter').modal('hide');
                                this.refreshData();
                            }
                        })
                        .catch(err=>console.log('error', err));
                    }
                    else{
                        let _id= this.sub_specialty.id;
                        fetch(`sub_specialty/${_id}`,{
                            method:'put',
                            body: JSON.stringify(this.sub_specialty),
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
                                this.sub_specialty.name='';
                                this.sub_specialty.arabic_name='';
                                this.sub_specialty.order='0';
                                this.selected = null;
                                $('#SubSpecialtyModalCenter').modal('hide');
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
                this.sub_specialty.id = 0;
                this.sub_specialty.name='';
                this.sub_specialty.arabic_name='';
                this.sub_specialty.order='0';
                this.selected = null;
                $('#SubSpecialtyModalCenter').modal('show');
            },
            editRow(obj){
                this.edit = true;
                this.sub_specialty.id = obj.id;
                this.sub_specialty.name = obj.name;
                this.sub_specialty.arabic_name = obj.arabic_name;
                this.sub_specialty.order = obj.order;
                this.selected = obj.specialty_id;
                $('#SubSpecialtyModalCenter').modal('show');
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
            },
            fetchSpecialtiesSelectList(){
                setTimeout(() => {
                    let vm = this;
                    vm.Shown = true;
                }, 800);
                fetch('/allSpecialties')
                .then(res=>res.json())
                .then(res=>{
                    var dict = []; 
                    dict.push({
                        value:null,
                        text:"select"
                    });
                    for(var i=0;i<res.length;i++)
                        dict.push({
                            value: res[i].id,
                            text: res[i].name
                        });
                    this.options=dict;
                })
                .catch(err=>console.log('error', err));
            }
        },
        components:{
            GridComponent
        }
    }
</script>
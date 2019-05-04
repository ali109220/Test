<template>
    <div class="container m-3">
        <div class="row">
            <div class="col-md-6">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AreaModalCenter">
                    Add New
                </button>

                <!-- Modal -->
                <div class="modal fade" id="AreaModalCenter" tabindex="-1" role="dialog" aria-labelledby="AreaModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalLongTitle">Add New Area</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="addArea">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="Name" v-model="area.name" v-validate="'required|max:50|min:2'">
                                        <div class="help-block alert alert-danger" v-show="errors.has('name')">
                                           {{errors.first('name')}}
                                        </div>
                                        <br>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="arabic-name" class="form-control" placeholder="Arabic Name" v-model="area.arabic_name" v-validate="'required|max:50|min:2'">
                                        <div class="help-block alert alert-danger" v-show="errors.has('arabic_name')">
                                           {{errors.first('arabic_name')}}
                                        </div>
                                        <br>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" name="order" class="form-control" placeholder="Order" v-model="area.order" v-validate="'required'">
                                        <div class="help-block alert alert-danger" v-show="errors.has('order')">
                                           {{errors.first('order')}}
                                        </div>
                                        <div class="help-block alert alert-danger" v-show="existErr('order')">
                                           {{getErr('order')}}
                                        </div>
                                        <br>
                                    </div>
                                    <div class="form-group">
                                        <b-form-select class="form-control" name="city_select" v-model="selected" :options="options" v-validate="'required'"/>
                                        <div class="help-block alert alert-danger" v-show="errors.has('city_select')">
                                           {{errors.first('city_select')}}
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button class="btn btn-primary" type="submit" v-show="area.name && area.arabic_name">Save</button>
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
        name:'AddAreaComponentVue',
        data(){
            return{
            area:{
                id:'',
                name:'',
                arabic_name:'',
                order: 0,
                city_id: ''
            },
            errs:[],
            selected: null,
            options: [],
            }
        },
        created(){
            this.fetchCitiesSelectList();
        },
        methods:{
            addArea(){
                //const isValid = await this.$refs.observer.validate();
                debugger;
                this.area.city_id = this.selected;
                if(!this._validate()){
                    this.errs = [];
                    fetch('area',{
                        method:'post',
                        body: JSON.stringify(this.area),
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
                            this.area.name='';
                            this.area.arabic_name='';
                            this.area.order='0';
                            this.area.city='select';
                            $('#AreaModalCenter').modal('hide')
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
            },
            fetchCitiesSelectList(){
            fetch('/allCities')
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
        }
                     
    }
</script>
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
                <div class="modal fade" id="DoctorModalCenter" tabindex="-1" role="dialog" aria-labelledby="DoctorModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalLongTitle">Add New Doctor</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="updateDoctor">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="Name" v-model="doctor.name" v-validate="'required|max:50|min:2'">
                                        <div class="help-block alert alert-danger" v-show="errors.has('name')">
                                           {{errors.first('name')}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="text" name="arabic_name" class="form-control" placeholder="Arabic Name" v-model="doctor.arabic_name" v-validate="'required|max:50|min:2'">
                                        <div class="help-block alert alert-danger" v-show="errors.has('arabic_name')">
                                           {{errors.first('arabic_name')}}
                                        </div>
                                    </div>
                                    <br>
                                    <input name="image" style="display:none" type="file" class="form-control" @change="onSelectedImage" ref="on_selectFile" v-validate="'image'">
                                    <a class="btn btn-primary" @click="$refs.on_selectFile.click()" style="margin-bottom: 30px;">Upload Image</a>
                                    <div class="help-block alert alert-danger" v-show="errors.has('image')">
                                         {{errors.first('image')}}
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <b-form-select class="form-control" name="title_select" v-model="title_selected" :options="title_options" v-validate="'required'"/>
                                        <div class="help-block alert alert-danger" v-show="errors.has('title_select')">
                                           {{errors.first('title_select')}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <b-form-select class="form-control" name="gender_select" v-model="gender_selected" :options="gender_options" v-validate="'required'"/>
                                        <div class="help-block alert alert-danger" v-show="errors.has('gender_select')">
                                           {{errors.first('gender_select')}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <b-form-select class="form-control" name="specialty_select" v-model="specialty_selected" :options="specialty_options" v-validate="'required'"/>
                                        <div class="help-block alert alert-danger" v-show="errors.has('specialty_select')">
                                           {{errors.first('specialty_select')}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <b-form-select class="form-control" name="sub_specialty_select" v-model="sub_specialty_selected" :options="sub_specialty_options"/>
                                        <div class="help-block alert alert-danger" v-show="errors.has('sub_specialty_select')">
                                           {{errors.first('sub_specialty_select')}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <b-form-select class="form-control" name="city_select" v-model="city_selected" :options="city_options" v-validate="'required'"/>
                                        <div class="help-block alert alert-danger" v-show="errors.has('city_select')">
                                           {{errors.first('city_select')}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <b-form-select class="form-control" name="area_select" v-model="area_selected" :options="area_options"/>
                                        <div class="help-block alert alert-danger" v-show="errors.has('area_select')">
                                           {{errors.first('area_select')}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="text" name="mobile_phone" class="form-control" placeholder="Mobile Phone" v-model="doctor.mobile_phone">
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="text" name="clinic_phone" class="form-control" placeholder="Clinic Phone" v-model="doctor.clinic_phone">
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="text" name="address" class="form-control" placeholder="Address" v-model="doctor.address">
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="text" name="arabic_address" class="form-control" placeholder="Arabic Address" v-model="doctor.arabic_address">
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="number" name="detection_fee" class="form-control" placeholder="Detection Fee" v-model="doctor.detection_fee">
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="number" name="wiating_duration" class="form-control" placeholder="Wiating Duration" v-model="doctor.wiating_duration">
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="time" name="first_appointment_morning" class="form-control" placeholder="First Appointment Morning" v-model="doctor.first_appointment_morning">
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="time" name="last_appointment_morning" class="form-control" placeholder="Last Appointment Morning" v-model="doctor.last_appointment_morning">
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="number" name="step_morning" class="form-control" placeholder="Step Morning" v-model="doctor.step_morning">
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="number" name="step_night" class="form-control" placeholder="Step Night" v-model="doctor.step_night">
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="time" name="first_appointment_night" class="form-control" placeholder="First Appointment Night" v-model="doctor.first_appointment_night">
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="time" name="last_appointment_night" class="form-control" placeholder="lLast Appointment Night" v-model="doctor.last_appointment_night">
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="checkbox" name="priority_from_here" class="form-control" placeholder="Priority From Here" v-model="doctor.priority_from_here">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button class="btn btn-primary" type="submit" v-show="doctor.name && doctor.arabic_name">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <GridComponent v-if="Shown" :columns="columns" :readUrl="url" :references="references" :enums="enums" @doubleClick="showRow" @editObject="editRow" @rowSelect="select_row"></GridComponent>
        <div v-show="row_select">
            <form @submit.prevent="addInsurance">
                <div class="form-group">
                    <b-form-select class="form-control" name="insurance_select" v-model="insurance_select" :options="insurance_options"/>
                </div>
                <br>
                <button class="btn btn-primary" type="submit">Add</button>
            </form>
            <div class="row">
                <div class="col-md-6">
                    <p v-for="insurance in insurances" v-bind:key="insurance.id">{{insurance.name}}</p>
                </div>
            </div>
        </div>
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
                    },
                    {
                        name:'sub_specialty_name',
                        url: '/allSubSpecialties'
                    },
                    {
                        name:'city_name',
                        url: '/allCities'
                    },
                    {
                        name:'area_name',
                        url: '/allAreas'
                    }
                ],
                enums:[
                    {
                        name:  'title',
                        url: '/allTitles'
                    },
                    {
                        name: 'gender',
                        url: '/allGenders'
                    }
                ],
                url:'/doctor/index',
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
                        name:'title',
                        title:'Title',
                        type:'enum',
                        number:false,
                        isDate : false,
                        isTime : false,
                        isBoolean : false,
                        isReference : false,
                        isImage:false,
                        isEnum:true
                    },
                    {
                        name:'gender',
                        title:'Gender',
                        type:'enum',
                        number:false,
                        isDate : false,
                        isTime : false,
                        isBoolean : false,
                        isReference : false,
                        isImage:false,
                        isEnum:true
                    },
                    {
                        name:'specialty_name',
                        title:'Specialty',
                        type:'reference',
                        number:false,
                        isDate : false,
                        isTime : false,
                        isBoolean : false,
                        isReference : true,
                        isImage:false,
                        isEnum:false
                    },
                    {
                        name:'sub_specialty_name',
                        title:'Sub Specialty',
                        type:'reference',
                        number:false,
                        isDate : false,
                        isTime : false,
                        isBoolean : false,
                        isReference : true,
                        isImage:false,
                        isEnum:false
                    },
                    {
                        name:'city_name',
                        title:'City',
                        type:'reference',
                        number:false,
                        isDate : false,
                        isTime : false,
                        isBoolean : false,
                        isReference : true,
                        isImage:false,
                        isEnum:false
                    },
                    {
                        name:'area_name',
                        title:'Area',
                        type:'reference',
                        number:false,
                        isDate : false,
                        isTime : false,
                        isBoolean : false,
                        isReference : true,
                        isImage:false,
                        isEnum:false
                    },
                    {
                        name:'mobile_phone',
                        title:'Mobile Phone',
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
                        name:'clinic_phone',
                        title:'Clinic Phone',
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
                        name:'address',
                        title:'Address',
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
                        name:'arabic_address',
                        title:'Arabic Address',
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
                        name:'detection_fee',
                        title:'Detection Fee',
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
                        name:'wiating_duration',
                        title:'Wiating Duration',
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
                        name:'first_appointment_morning',
                        title:'First Appointment Morning',
                        type:'time',
                        number:false,
                        isDate : false,
                        isTime : true,
                        isBoolean : false,
                        isReference : false,
                        isImage:false,
                        isEnum:false
                    },
                    {
                        name:'last_appointment_morning',
                        title:'Last Appointment Morning',
                        type:'time',
                        number:false,
                        isDate : false,
                        isTime : true,
                        isBoolean : false,
                        isReference : false,
                        isImage:false,
                        isEnum:false
                    },
                    {
                        name:'step_morning',
                        title:'Step Morning',
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
                        name:'step_night',
                        title:'Step Night',
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
                        name:'first_appointment_night',
                        title:'First Appointment Night',
                        type:'time',
                        number:false,
                        isDate : false,
                        isTime : true,
                        isBoolean : false,
                        isReference : false,
                        isImage:false,
                        isEnum:false
                    },
                    {
                        name:'last_appointment_night',
                        title:'Last Appointment Night',
                        type:'time',
                        number:false,
                        isDate : false,
                        isTime : true,
                        isBoolean : false,
                        isReference : false,
                        isImage:false,
                        isEnum:false
                    },
                    {
                        name:'priority_from_here',
                        title:'Priority From Here',
                        type:'boolean',
                        number:false,
                        isDate : false,
                        isTime : false,
                        isBoolean : true,
                        isReference : false,
                        isImage:false,
                        isEnum:false
                    }
                ],
                doctor:{
                id:'',
                name:'',
                arabic_name:'',
                img_src:'',
                title:'',
                gender:'',
                specialty_id:'',
                sub_specialty_id:'',
                city_id:'',
                area_id:'',
                mobile_phone:'',
                clinic_phone:'',
                address:'',
                arabic_address:'',
                detection_fee:'',
                wiating_duration:'',
                first_appointment_morning:'',
                last_appointment_morning:'',
                step_morning:'',
                step_night:'',
                first_appointment_night:'',
                last_appointment_night:'',
                priority_from_here:''
                },
                insurances: [],
                row_select: false,
                selected_id : 0,
                errs:[],
                edit:false,
                specialty_selected: null,
                sub_specialty_selected: null,
                city_selected: null,
                area_selected: null,
                insurance_select: null,
                title_selected: 0,
                gender_selected: 0,
                specialty_options: [],
                sub_specialty_options: [],
                city_options: [],
                area_options: [],
                insurance_options: [],
                title_options: [
                    {
                        value:0,
                        text:"specialist"
                    },
                    {
                        value:1,
                        text:"professor"
                    },
                    {
                        value:2,
                        text:"consultant"
                    }
                ],
                gender_options: [
                    {
                        value:0,
                        text:"Male"
                    },
                    {
                        value:1,
                        text:"Female"
                    }
                ],
                Shown: false
            }
        },
        beforeMount(){
            this.fetchSpecialtiesSelectList();
            this.fetchSubSpecialtiesSelectList();
            this.fetchCitiesSelectList();
            this.fetchAreasSelectList();
        },
        methods:{
            updateDoctor(){
                this.doctor.specialty_id = this.specialty_selected;
                this.doctor.sub_specialty_id = this.sub_specialty_selected;
                this.doctor.city_id = this.city_selected;
                this.doctor.area_id = this.area_selected;
                this.doctor.title = this.title_selected;
                this.doctor.gender = this.gender_selected;
                this.doctor.first_appointment_morning = "2000-01-01 "+this.doctor.first_appointment_morning;
                this.doctor.last_appointment_morning = "2000-01-01 "+this.doctor.last_appointment_morning;
                this.doctor.first_appointment_night = "2000-01-01 "+this.doctor.first_appointment_night;
                this.doctor.last_appointment_night = "2000-01-01 "+this.doctor.last_appointment_night;
                if(!this._validate()){
                    this.errs = [];
                    if(this.edit === false){
                        fetch('doctor',{
                            method:'post',
                            body: JSON.stringify(this.doctor),
                            headers:{
                               'content-type' : 'application/json',
                               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).then(res=>console.log(res.json()))
                        .then(res=>{
                            if(res.error != null){
                                var _err = {
                                    name : res.error,
                                    msg : res.msg
                                }
                                this.errs.push(_err);
                            }
                            else{
                                this.clearModal();
                                $('#DoctorModalCenter').modal('hide');
                                this.refreshData();
                            }
                        })
                        .catch(err=>console.log('error', err));
                    }
                    else{
                        let _id= this.doctor.id;
                        fetch(`doctor/${_id}`,{
                            method:'put',
                            body: JSON.stringify(this.doctor),
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
                                this.clearModal();
                                $('#DoctorModalCenter').modal('hide');
                                this.refreshData();
                            }
                        })
                        .catch(err=>console.log('error', err));
                    }
                }
            },
            clearModal(){
                this.doctor.id = 0;
                this.doctor.name='';
                this.doctor.arabic_name='';
                this.doctor.img_src='';
                this.doctor.title='';
                this.doctor.gender='';
                this.specialty_selected = null;
                this.sub_specialty_selected = null;
                this.city_selected = null;
                this.area_selected = null;
                this.doctor.mobile_phone = '';
                this.doctor.clinic_phone = '';
                this.doctor.address = '';
                this.doctor.arabic_address = '';
                this.doctor.detection_fee = '';
                this.doctor.wiating_duration = '';
                this.doctor.first_appointment_morning = '';
                this.doctor.first_appointment_night = '';
                this.doctor.step_morning = '';
                this.doctor.step_night = '';
                this.doctor.last_appointment_morning = '';
                this.doctor.last_appointment_night = '';
                this.doctor.priority_from_here = '';                     
            },
            refreshData(){
                this.$root.$emit('refreshDataGrid');
            },
            showRow(id, obj){
                console.log('obj', obj);
            },
            select_row(id){
                debugger;
                this.row_select = true;
                this.selected_id = id;
                this.fetchInsurancesSelectList(id);
            },
            showPopup(){
                this.edit = false;
                this.clearModal();
                $('#DoctorModalCenter').modal('show');
            },
            editRow(obj){
                this.edit = true;
                this.doctor.id = obj.id;
                this.doctor.name = obj.name;
                this.doctor.arabic_name = obj.arabic_name;
                this.specialty_selected = obj.specialty_id;
                this.sub_specialty_selected = obj.sub_specialty_id;
                this.city_selected = obj.city_id;
                this.area_selected = obj.area_id;
                this.doctor.mobile_phone = obj.mobile_phone;
                this.doctor.clinic_phone = obj.clinic_phone;
                this.doctor.address = obj.address;
                this.doctor.arabic_address = obj.arabic_address;
                this.doctor.detection_fee = obj.detection_fee;
                this.doctor.wiating_duration = obj.wiating_duration;
                this.doctor.first_appointment_morning = obj.first_appointment_morning;
                this.doctor.first_appointment_night = obj.first_appointment_night;
                this.doctor.step_morning = obj.step_morning;
                this.doctor.step_night = obj.step_night;
                this.doctor.last_appointment_morning = obj.last_appointment_morning;
                this.doctor.last_appointment_night = obj.last_appointment_night;
                this.doctor.priority_from_here = obj.priority_from_here;
                $('#DoctorModalCenter').modal('show');
            },
            addInsurance(){
                debugger;
                var doctor_insurance = {
                    doctor_id : this.selected_id,
                    insurance_id : this.insurance_select
                }
                fetch('addInsurance',{
                            method:'post',
                            body: JSON.stringify(doctor_insurance),
                            headers:{
                               'content-type' : 'application/json',
                               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                }).then(res=>console.log(res.json()))
                .catch(err=>console.log('error', err));
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
                    this.specialty_options=dict;
                })
                .catch(err=>console.log('error', err));
            },
            fetchSubSpecialtiesSelectList(){
                fetch('/allSubSpecialties')
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
                    this.sub_specialty_options=dict;
                })
                .catch(err=>console.log('error', err));
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
                    this.city_options=dict;
                })
                .catch(err=>console.log('error', err));
            },
            fetchAreasSelectList(){
                setTimeout(() => {
                    let vm = this;
                    vm.Shown = true;
                }, 800);
                fetch('/allAreas')
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
                    this.area_options=dict;
                })
                .catch(err=>console.log('error', err));
            },
            fetchInsurancesSelectList(id){
                fetch(`allInsurancesWithoutAdded/${id}`)
                .then(res=>res.json())
                .then(res=>{
                    var dict = []; 
                    dict.push({
                        value:null,
                        text:"select"
                    });
                    console.log('response', res)
                    for(var i=0;i<res.length;i++)
                        dict.push({
                            value: res[i].id,
                            text: res[i].name
                        });
                    this.insurance_options=dict;
                    this.insurance_select = null;
                })
                .catch(err=>console.log('error', err));
            },
            onSelectedImage(e){
                
                console.log('image', this.doctor.img_src);
                var reader = new FileReader();
                reader.readAsDataURL(e.target.files[0]);
                let vm = this;
                reader.onload = (event) => {
                    vm.doctor.img_src = event.target.result;
                }
                
            }
        },
        components:{
            GridComponent
        }
    }
</script>
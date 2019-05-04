<template>
    <div id="app" class="container">
    <div style="overflow-x:auto;">
        <table class="table" style="position: relative;
    overflow: auto;border-collapse: collapse;
                                    border-spacing: 0;width: 100%;border: 1px solid #ddd;    height: 410px;">
            <thead class="thead-dark">
                <tr>
                    <th style="text-align: left;padding: 8px;width: 50px;" scope="col"></th>
                    <th style="text-align: left;height: 74px;
    overflow-wrap: break-word;
    padding-right: 8px;" scope="col"  v-for="col in columns" v-bind:key="col.name">
                        <span style="padding-right: 35px;">{{col.title}}</span>
                        <div class="popup" style="margin-left: 250px;top:-22px">
                            <span @click="showPopUp(col.name)">F</span>
                            <div class="popuptext popUpFilter" :id="col.name"  style="width: 161px;height: 142px;left: -175px;">
                                <div class="container">
                                    <b-form-select v-if="!col.number&&!col.isDate&&!col.isTime&&!col.isReference" class="form-control filter-input-select" :id="col.name+col.type+col.name" :name="col.name+col.type+col.name" :options="filter_str_options"/>
                                    <b-form-select v-if="col.number||col.isDate||col.isTime" class="form-control filter-input-select" :id="col.name+col.type+col.name" :name="col.name+col.type+col.name" :options="filter_num_options"/>
                                    <b-form-select v-if="col.isReference" class="form-control filter-input-select" :id="col.name+col.type+col.name" :name="col.name+col.type+col.name" :options="filter_ref_options" />
                                    
                                    <input v-if="!col.number&&!col.isDate&&!col.isTime&&!col.isBoolean&&!col.isReference&&!col.isEnum" :id="col.name+col.type" type="text" class="form-control filter-input-select">
                                    <input v-if="col.isDate" :id="col.name+col.type" type="date" class="form-control filter-input-select">
                                    <input v-if="col.number" :id="col.name+col.type" type="number" class="form-control filter-input-select">
                                    <input v-if="col.isTime" :id="col.name+col.type" type="time" class="form-control filter-input-select">
                                    <fieldset v-if="col.isBoolean" :id="col.name+col.type" @click="fieldSetOnChange(col.name, col.type)">
                                        <input :name="col.name+col.type" type="radio" value="1" style="margin-left: 0;width: 26px;margin-top: 9px;height: 14px;">
                                        <span>True</span>
                                        <input :name="col.name+col.type" type="radio" value="0" style="margin-left: 6px;width: 26px;margin-top: 6px;height: 14px;">
                                        <span>False</span>
                                    </fieldset>
                                    <b-form-select v-if="col.isReference" class="form-control filter-input-select" :id="col.name+col.type" :name="col.name+col.type" :options="getSelectOptions(col.name)"/>
                                    <b-form-select v-if="col.isEnum" class="form-control filter-input-select" :id="col.name+col.type" :name="col.name+col.type" :options="getSelectEnumOptions(col.name)"/>
                                    <a class="btn btn-secondary btn-primary" style="margin-left: 0px; margin-top: 6px;" href="#" @click="filter(col.name, col.type)">Filter</a>
                                    <a class="btn btn-secondary btn-primary" style="margin-left: 6px; margin-top: 6px;" href="#" @click="clearFilter(col.name, col.type)">clear</a>
                                </div>
                            </div>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj in objsData" v-bind:key="obj.id" :id="obj.id+'row'" @dblclick="double_click(obj.id)" @click="row_select(obj.id)" class="row-grid">
                    <span class="span-action-list" @click="openActionList(obj.id)">=</span>
                    <div :id="obj.id+'actionListPopup'" class="action-list-popup hide-popup">
                        <a class="btn-action-list-element" @click="edit_element(obj)">Edit</a>
                        <br>
                        <a class="btn-action-list-element" @click="remove_element(obj.id)">Delete</a>
                    </div>
                    
                        <td v-for="col in _props.columns" v-bind:key="col.name" style="text-align: left;padding: 8px;word-wrap:break-word">{{getValueOfCol(col.name,obj.id)}}</td>
                    
                    
                </tr>
            </tbody>
        </table>
        </div>
        <nav aria-label="Page navigation example">
           <ul class="pagination">
               <li v-bind:class="[{ disabled : !pagination.prev_page}]" class="page-item"><a class="page-link" @click="fetchobjsData(pagination.prev_page)">Previous</a></li>
               <li class="page-item disabled"><a class="page-link">{{pagination.current_page}} of {{pagination.last_page}}</a></li>
               <li v-bind:class="[{ disabled : !pagination.next_page}]" class="page-item"><a class="page-link" @click="fetchobjsData(pagination.next_page)">Next</a></li>
           </ul>
        </nav>
    </div>
</template>
<script>
    export default {
        name:'Grid-Vue',
        props:{
            columns:{
                type: Array,
                required: true
            },
            readUrl: {
                type: String,
                required: true
            },
            references: {
                type: Array,
                required: false
            },
            enums: {
                type: Array,
                required: false
            }
        },
        data(){
            return{
                filters :[],
                selectNum:0,
                pagination:{},
                select: 'select',
                options: [],
                enum_options: [],
                objsData:[],
                filter_str_options:[
                    {
                    value : null,
                    text : 'select'
                    },
                    {
                    value : 0,
                    text : 'equal'
                    },
                    {
                    value : 1,
                    text : 'start with'
                    },
                    {
                    value : 2,
                    text : 'contain'
                    },
                    {
                    value : 3,
                    text : 'end with'
                    },
                    {
                    value : 4,
                    text : 'not equal'
                    }
                ],
                filter_num_options:[
                    {
                    value : null,
                    text : 'select'
                    },
                    {
                    value : 0,
                    text : 'equal'
                    },
                    {
                    value : 1,
                    text : 'GE'
                    },
                    {
                    value : 2,
                    text : 'GT'
                    },
                    {
                    value : 3,
                    text : 'LE'
                    },
                    {
                    value : 4,
                    text : 'LT'
                    },
                    {
                    value : 5,
                    text : 'not equal'
                    }
                ],
                filter_ref_options:[
                    {
                    value : null,
                    text : 'select'
                    },
                    {
                        value : 0,
                        text : 'equal' 
                    },
                    {
                        value : 1,
                        text : 'not equal'
                    }
                ]
            }
        },
        created() {
            this.fetchobjsData();
            this.setDefalutValues();
        },
        beforeMount(){
            this.fetchReferencesData();
            this.fetchEnumsData();
        },
        mounted() {
             this.$root.$on('refreshDataGrid', () => {
                this.fetchobjsData();
           })
        },
        methods:{
            openActionList(id){
                var el = "#"+id+"actionListPopup";
                if($(el).hasClass( "hide-popup" )){
                    $(el).removeClass( "hide-popup" );
                    $(el).addClass( "show-popup" );
                }
                else{
                    $(el).removeClass( "show-popup" );
                    $(el).addClass( "hide-popup" );
                }
                $( ".action-list-popup" ).each(function( index, element ) {
                    if ( $( this ).is(el) ) {
                    }
                    else{
                        $( element ).addClass( "hide-popup" );
                        $( element ).removeClass( "show-popup" );
                    }
                });
                
            },
            getSelectValue(name, type){
                return 'select';
            },
            getSelectOptions(name){
                    var data = [];
                    let vm = this;
                    var ops = vm.options;
                    ops.forEach(option => {
                        if(option.ref_name == name)
                            data = option.ref_options;
                    });
                    return data;
                    
            },
            getSelectEnumOptions(name){
                
                    var data = [];
                    let vm = this;
                    var ops = vm.enum_options;
                    ops.forEach(option => {
                        if(option.enum_name == name)
                            data = option.enum_options;
                    });
                    return data;
                    
            },
            fetchReferencesData(){
                let vm = this;
                var url = '';
                var refs = vm._props.references;
                if(refs!=null)
                refs.forEach(ref => {
                    vm.fetchRefereneSelectList(ref.name, ref.url);
                });
            },
            fetchEnumsData(){
                let vm = this;
                var url = '';
                var refs = vm._props.enums;
                if(refs!=null)
                refs.forEach(ref => {
                    vm.fetchEnumSelectList(ref.name, ref.url);
                });
            },
            async fetchRefereneSelectList(name, url){
                try{
                    var dict = []; 
                    const resp = await fetch(url);
                    const newResp = await resp.json();
                    dict.push({
                        value:null,
                    text:"select"
                });
                if(newResp != null)
                    for(var i=0;i<newResp.length;i++)
                        dict.push({
                            value: newResp[i].id,
                            text: newResp[i].name
                        });
                var option_obj ={
                    ref_name : name,
                    ref_options : dict
                }
                this.options.push(option_obj);
                console.log('res', newResp);
                }catch(err){
                    console.log('err', err)
                } 
            },
            async fetchEnumSelectList(name, url){
                try{
                    var dict = []; 
                    const resp = await fetch(url);
                    const newResp = await resp.json(); 
                    if(newResp != null)
                        for(var i=0;i<newResp.length;i++)
                            dict.push({
                                value: newResp[i].id,
                                text: newResp[i].name
                            });
                    var option_obj ={
                        enum_name : name,
                        enum_options : dict
                    }
                    this.enum_options.push(option_obj);
                    console.log('res', newResp);
                }catch(err){
                    console.log('err', err)
                } 
            },
            edit_element(obj){
                $( ".action-list-popup" ).addClass( "hide-popup" );
                $( ".action-list-popup" ).removeClass( "show-popup" );
                this.$emit('editObject', obj);
            },
            double_click(id){
                var _obj = null;
                (this.objsData).forEach(obj => {
                    if(obj.id == id)
                    _obj = obj;
                });
                this.$emit('doubleClick', id, _obj);
            },
            row_select(id){
                debugger;
                var el = "#"+id+'row' 
                if($(el).hasClass( "row-selected" )){
                }
                else{
                    $(el).addClass( "row-selected" );
                }
                $( ".row-grid" ).each(function( index, element ) {
                    if ( $( this ).is(el) ) {
                    }
                    else{
                        $( element ).removeClass( "row-selected" );
                    }
                });
                this.$emit('rowSelect', id);
            },
            getWhereFilter(filter){
                var fWhereObj = {
                    field:filter.colName,
                    operator:'',
                    value:'',
                    type : 'text'
                    };
                if(filter.colType == 0){
                    //0 refer to text
                    fWhereObj.operator = 'like';
                    switch(filter.type)
                    {
                        case 0 :
                           fWhereObj.value = filter.text;
                           break;
                        case 1:
                            fWhereObj.value = filter.text+'%';
                            break;
                        case 2:
                            fWhereObj.value = '%'+filter.text+'%';
                            break;
                        case 3:
                            fWhereObj.value = '%'+filter.text;
                            break;
                        case 4:
                            fWhereObj.operator = '<>';
                            fWhereObj.value = filter.text;
                            break;
                    }
                    return fWhereObj;
                }
                else if(filter.colType == 1 || filter.colType == 2 || filter.colType == 3){
                    //0 refer to number
                    if(filter.colType == 2)
                        fWhereObj.type = 'date';
                    else if(filter.colType == 3)
                        fWhereObj.type = 'time';
                    fWhereObj.value = filter.text;
                    switch(filter.type)
                    {
                        case 0 :
                            fWhereObj.operator = '=';
                           break;
                        case 1:
                            fWhereObj.operator = '>';
                            break;
                        case 2:
                            fWhereObj.operator = '>=';
                            break;
                        case 3:
                            fWhereObj.operator = '<';
                            break;
                        case 4:
                            fWhereObj.operator = '<=';
                            break;
                        case 5:
                            fWhereObj.operator = '<>';
                            break;
                    }
                    return fWhereObj;
                }
                else if(filter.colType == 4 || filter.colType == 5){
                    if(filter.colType == 4){
                    var strs = fWhereObj.field.split('_');
                    fWhereObj.field = strs[0]+'_id';
                    
                    fWhereObj.type = 'reference';
                    }
                    else{
                        fWhereObj.type = 'enum';   
                    }
                    fWhereObj.value = filter.text;
                    switch(filter.type)
                    {
                        case 0 :
                            fWhereObj.operator = '=';
                           break;
                        case 1:
                            fWhereObj.operator = '<>';
                            break;
                    }
                    return fWhereObj;
                }
            },
            getWhereFilters(){
                var where_filters =[];
                (this.filters).forEach(filter => {
                    var where_filter = this.getWhereFilter(filter);
                    where_filters.push(where_filter);
                });
                var WhereWithLenght = {
                    where_filters : where_filters,
                    length : where_filters.length
                };
                return WhereWithLenght;
            },
            fieldSetOnChange(colName, colType){
                var _id = colName+colType;
                    $('#'+_id+' input:radio').on('change', function() {
                       var value = $(this).val();
                       alert(value);      
                    });
            },
            filter(colName, colType){ 
                var element_id = "#"+colName+colType+colName;
                var _select = $(element_id).find(":selected").val();
                if(this.selectedText != 'select'){
                    this.filters.forEach(filter => {
                        if(filter.colName == colName)
                           this.filters.pop(filter);
                    });  
                    var element_id = "#"+colName+colType;
                    var input = '';
                    if(colType == 'reference' || olType == 'enum' )
                        input = $(element_id).find(":selected").val();
                    else
                        input = $(element_id).val();
                    var filterObj = {
                    text: null,
                    type:'',
                    colType:0,
                    colName:''
                    };
                    filterObj.text = input;
                    filterObj.type = parseInt(_select, 10);
                    filterObj.colName = colName;
                    switch(colType){
                        case 'text' : 
                           filterObj.colType = 0;
                           break;
                        case 'number' : 
                           filterObj.colType = 1;
                           break;
                        case 'date' : 
                           filterObj.colType = 2;
                           break;
                        case 'time' : 
                           filterObj.colType = 3;
                           break;
                        case 'reference' : 
                           filterObj.colType = 4;
                           break;
                        case 'enum' : 
                           filterObj.colType = 5;
                           break;
                    }
                    this.filters.push(filterObj);
                    var popup = document.getElementById(colName);
                    popup.classList.remove("show");
                    this.fetchobjsData();
                }
            },
            clearFilter(name,type){
                this.filters.forEach(filter => {
                    if(filter.colName == name)
                        this.filters.pop(filter);
                });
                var element_id_op = "#"+name+type+name;
                var element_id_val = "#"+name+type;
                $(element_id_op).val(null);
                if(type=='reference' || type=='enum')
                    $(element_id_val).val(null);
                else
                    $(element_id_val).val('');
                var popup = document.getElementById(name);
                popup.classList.remove("show");
                this.fetchobjsData();
            },
             getDate(d) {
                 var arrDT = d.split('T');
                 var date = arrDT[0].split('-');
                return date[2]+'/'+date[1]+'/'+date[0];
             },
              getTime(d) {
                 var arrDT = d.split('T');
                 var time = arrDT[1].split(':');
                return time[0]+':'+time[1];
             },
            showPopUp(name){
                var popup = document.getElementById(name);
                if(popup.classList.contains("show"))
                    popup.classList.remove("show");
                else
                    popup.classList.add("show");
            },
            fetchobjsData(page_url){
                var filtersWhere = this.getWhereFilters();
                let vm = this;
                page_url = page_url || vm._props.readUrl;
                fetch(page_url,{
                        method:'post',
                        body: JSON.stringify(filtersWhere),
                        headers:{
                            'content-type' : 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                .then(res=>res.json())
                .then(res=>{
                    let vm= this;
                    vm.objsData=res.data;
                    vm.makePagination(res.meta,res.links);
                })
                .catch(err=>console.log('error', err));
            },
            makePagination(_meta,_links){
                let _pagination = {
                    current_page : _meta.current_page,
                    last_page: _meta.last_page,
                    next_page : _links.next,
                    prev_page : _links.prev
                }
                this.pagination = _pagination;
            },
            getValueOfCol(name, id){
                for(var i=0; i<this.objsData.length; i++)
                    if(this.objsData[i].id == id)
                        return this.objsData[i][name];
            },
            setDefalutValues(){
                setTimeout(() => {
                    this._props.columns.forEach(col => {
                    var element_id_op = "#"+col.name+col.type+col.name;
                    var element_id_val = "#"+col.name+col.type;

                    $(element_id_op).val(null);
                    if(col.type == 'reference' || col.type=='enum')
                        $(element_id_val).val(null);
                    });
                }, 600);
                
            }
        }
    }
</script>
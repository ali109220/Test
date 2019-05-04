<template>
    <div id="app" class="container">
        <AddCityComponentVue></AddCityComponentVue>
    <div style="overflow-x:auto;">
        <table class="table" style="table-layout: fixed;border-collapse: collapse;
                                    border-spacing: 0;width: 100%;border: 1px solid #ddd;">
            <thead class="thead-dark">
                <tr>
                    <th style="text-align: left;padding: 8px;" scope="col">#</th>
                    <th style="text-align: left;word-wrap:break-word;padding: 8px;" scope="col" v-for="col in columns" v-bind:key="col.name">
                        {{col.title}}
                        <div class="popup">
                            <span @click="showPopUp(col.name)">F</span>
                            <div class="popuptext popUpFilter" :id="col.name"  style="width: 185px;height: 140px;left: -175px;">
                                <div class="dropdown">
                                    <div class="dropdown" :id="col.type+col.name">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" :id="col.name+col.type+col.name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Select
                                        </button>
                                        <div v-if="!col.number&&!col.isDate&&!col.isTime" class="dropdown-menu">
                                            <a class="dropdown-item" href="#" style="    height: 25px;" @click="ChangeDropDownValue(1,col.name,col.type)">equal</a>
                                            <a class="dropdown-item" href="#" style="    height: 25px;" @click="ChangeDropDownValue(2,col.name,col.type)">start with</a>
                                            <a class="dropdown-item" href="#" style="    height: 25px;" @click="ChangeDropDownValue(3,col.name,col.type)">contain</a>
                                            <a class="dropdown-item" href="#" style="    height: 25px;" @click="ChangeDropDownValue(4,col.name,col.type)">end with</a>
                                            <a class="dropdown-item" href="#" style="    height: 25px;" @click="ChangeDropDownValue(5,col.name,col.type)">not equal</a>
                                        </div>
                                        <div v-if="col.number||col.isDate||col.isTime" class="dropdown-menu">
                                            <a class="dropdown-item" href="#" style="    height: 25px;" @click="ChangeDropDownValue(1,col.name,col.type)">equal</a>
                                            <a class="dropdown-item" href="#" style="    height: 25px;" @click="ChangeDropDownValue(2,col.name,col.type)">GT</a>
                                            <a class="dropdown-item" href="#" style="    height: 25px;" @click="ChangeDropDownValue(3,col.name,col.type)">GE</a>
                                            <a class="dropdown-item" href="#" style="    height: 25px;" @click="ChangeDropDownValue(4,col.name,col.type)">LT</a>
                                            <a class="dropdown-item" href="#" style="    height: 25px;" @click="ChangeDropDownValue(5,col.name,col.type)">LE</a>
                                            <a class="dropdown-item" href="#" style="    height: 25px;" @click="ChangeDropDownValue(6,col.name,col.type)">not equal</a>
                                        </div>
                                    </div>
                                    <input v-if="!col.number&&!col.isDate&&!col.isTime&&!col.isBoolean" :id="col.name+col.type" type="text" class="form-control" style="margin-left: 30px;width: 125px; margin-top: 6px;height: 32px;">
                                    <input v-if="col.isDate" :id="col.name+col.type" type="date" class="form-control" style="margin-left: 30px;width: 125px; margin-top: 6px;height: 32px;">
                                    <input v-if="col.number" :id="col.name+col.type" type="number" class="form-control" style="margin-left: 30px;width: 125px; margin-top: 6px;height: 32px;">
                                    <input v-if="col.isTime" :id="col.name+col.type" type="time" class="form-control" style="margin-left: 30px;width: 125px; margin-top: 6px;height: 32px;">
                                    <fieldset v-if="col.isBoolean" :id="col.name+col.type" @click="fieldSetOnChange(col.name, col.type)">
                                        <input :name="col.name+col.type" type="radio" value="1" style="margin-left: 0;width: 26px;margin-top: 9px;height: 14px;">
                                        <span>True</span>
                                        <input :name="col.name+col.type" type="radio" value="0" style="margin-left: 6px;width: 26px;margin-top: 6px;height: 14px;">
                                        <span>False</span>
                                    </fieldset>
                                    <a class="btn btn-secondary btn-primary" style="margin-left: 0px; margin-top: 6px;" href="#" @click="filter(col.name, col.type)">Filter</a>
                                    <a class="btn btn-secondary btn-primary" style="margin-left: 6px; margin-top: 6px;" href="#" @click="clearFilter(col.name, col.type)">clear</a>
                                </div>
                            </div>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="city in cities" v-bind:key="city.id" @dblclick="ShowInfo(city.id)">
                    <th scope="row" style="text-align: left;padding: 8px;">{{city.id}}</th>
                    <td v-for="col in columns" v-bind:key="col.name" style="text-align: left;padding: 8px;word-wrap:break-word">{{getValueOfCol(col.name,city.id)}}</td>
                </tr>
            </tbody>
        </table>
        </div>
        <nav aria-label="Page navigation example">
           <ul class="pagination">
               <li v-bind:class="[{ disabled : !pagination.prev_page}]" class="page-item"><a class="page-link" @click="fetchCities(pagination.prev_page)">Previous</a></li>
               <li class="page-item disabled"><a class="page-link">{{pagination.current_page}} of {{pagination.last_page}}</a></li>
               <li v-bind:class="[{ disabled : !pagination.next_page}]" class="page-item"><a class="page-link" @click="fetchCities(pagination.next_page)">Next</a></li>
           </ul>
        </nav>
    </div>
</template>
<script>
import AddCityComponentVue from './AddCityComponent'
    export default {
        data(){
            return{
                filters :[],
                selectNum:0,
                pagination:{},
                select: 'select',
                options: [],
                cities:[],
                columns:[
                    {
                        name:'name',
                        title:'Name',
                        type:'text',
                        number:false,
                        isDate : false,
                        isTime : false,
                        isBoolean : false
                    },
                    {
                        name:'arabic_name',
                        title:'Arabic Name',
                        type:'text',
                        number:false,
                        isDate : false,
                        isTime : false,
                        isBoolean : false
                    },
                    {
                        name:'order',
                        title:'Order',
                        type:'number',
                        number:true,
                        isDate : false,
                        isTime : false,
                        isBoolean : false
                    }
                ]
            }
        },
        beforeMount() {
            this.fetchCities();
        },
        methods:{
            ShowInfo(id){
                 alert(id);
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
                    debugger;
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
                        debugger;
                       var value = $(this).val();
                       alert(value);      
                    });
            },
            filter(colName, colType){
                debugger; 
                var selectedText = document.getElementById(colName+colType+colName).innerHTML;
                var selectNum = 0;
                if(this.selectedText != 'select'){
                    this.filters.forEach(filter => {
                        if(filter.colName == colName)
                           this.filters.pop(filter);
                    });
                    if(colType == 'text'){
                        switch(selectedText){
                            case 'equal':
                                selectNum = 0;
                                break;
                            case 'start with':
                                selectNum = 1;
                                break;
                            case 'contain':
                                selectNum = 2;
                                break;
                            case 'end with':
                                selectNum = 3;
                                break;
                            case 'not equal':
                                selectNum = 4;
                                break;
                        }
                    }
                    else if(colType == 'number' || colType == 'date' ||colType == 'time'){
                        switch(selectedText){
                            case 'equal':
                                selectNum = 0;
                                break;
                            case 'GT':
                                selectNum = 1;
                                break;
                            case 'GE':
                                selectNum = 2;
                                break;
                            case 'LT':
                                selectNum = 3;
                                break;
                            case 'LE':
                                selectNum = 4;
                                break;
                            case 'not equal':
                                selectNum = 5;
                                break;
                        }
                    }
                    var input = document.getElementById(colName+colType).value;
                    debugger;
                    /*if(colType == 'date')
                    {
                        var arrDT = input.split('/');
                        var date = new Date(2018,1,1, 00, 00, 00, 0);
                    }*/
                    var filterObj = {
                    text: null,
                    type:'',
                    colType:0,
                    colName:''
                    };
                    filterObj.text = input;
                    filterObj.type = selectNum;
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
                    }
                    this.filters.push(filterObj);
                    var popup = document.getElementById(colName);
                    popup.classList.remove("show");
                    this.fetchCities();
                }
            },
            clearFilter(name,type){
                this.filters.forEach(filter => {
                    if(filter.colName == name)
                        this.filters.pop(filter);
                });
                document.getElementById(name+type+name).innerHTML = 'select';
                document.getElementById(name+type).value = '';
                var popup = document.getElementById(name);
                popup.classList.remove("show");
                this.fetchCities();
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
            ChangeDropDownValue(id,colName,colType){
                if(colType == 'text')
                  switch(id){
                    case 1:
                        this.select='equal';
                        break;
                    case 2:
                        this.select='start with';
                        break;
                    case 3:
                        this.select='contain';
                        break;
                    case 4:
                        this.select='end with';
                        break;
                    case 5:
                        this.select='not equal';
                        break;
                  }
                else if(colType == 'number' || colType == 'date' || colType == 'time')
                  switch(id){
                    case 1:
                        this.select='equal';
                        break;
                    case 2:
                        this.select='GT';
                        break;
                    case 3:
                        this.select='GE';
                        break;
                    case 4:
                        this.select='LT';
                        break;
                    case 5:
                        this.select='LE';
                        break;
                    case 6:
                        this.select='not equal';
                        break;
                  }
                document.getElementById(colName+colType+colName).innerHTML = this.select;
            },
            showPopUp(name){
                var popup = document.getElementById(name);
                if(popup.classList.contains("show"))
                    popup.classList.remove("show");
                else
                    popup.classList.add("show");
            },
            fetchCities(page_url){
                var filtersWhere = this.getWhereFilters();
                let vm = this;
                page_url = page_url || 'city/index';
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
                    vm.cities=res.data;
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
                for(var i=0; i<this.cities.length; i++)
                    if(this.cities[i].id == id)
                        return this.cities[i][name];
            }
        },
        components:{
            AddCityComponentVue
        }
    }
</script>
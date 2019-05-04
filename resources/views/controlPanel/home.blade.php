@extends('layouts.app')

@section('content')
<div class="content" id="app">
   <ul class="nav nav-tabs">
    <li class="nav-item">
      <router-link class="nav-link" to="/cityV">City</router-link>
    </li>
    <li class="nav-item">
      <router-link class="nav-link" to="/areaV">Area</router-link>
    </li>
    <li class="nav-item">
      <router-link class="nav-link" to="/specailtyV">Specialty</router-link>
    </li>
    <li class="nav-item">
      <router-link class="nav-link" to="/sub_specailtyV">Sub Specialty</router-link>
    </li>
    <li class="nav-item">
      <router-link class="nav-link" to="/doctorV">Doctor</router-link>
    </li>
    <li class="nav-item">
      <router-link class="nav-link" to="/insuranceV">Inurance</router-link>
    </li>
   </ul>
  <router-view></router-view>
</div>

@endsection

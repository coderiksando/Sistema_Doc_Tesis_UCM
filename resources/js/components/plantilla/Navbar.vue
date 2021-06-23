<template>
  
    <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav ml-left">
     
      <li class="nav-item">
        <a class="nav-link menuBtn" data-widget="pushmenu" data-slide="true" href="" role="button">
          <i class="fa fa-bars"></i>
        </a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      
      <li class="nav-item">
        <a class="nav-link nav-btn" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

</template>

<script>
export default {
  props: ['ruta']

}
</script>

<style>

@media only screen and (min-width: 993px) {
    .menuBtn {
        display: none;
    }
}

</style>
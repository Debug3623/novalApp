 <aside class="main-sidebar">
   <!-- sidebar: style can be found in sidebar.less -->
   <section class="sidebar">
     <!-- Sidebar user panel -->
     <div class="user-panel hidden">
       <div class="pull-left image">
         <img src="assets/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
       </div>
       <div class="pull-left info">
         <p><?php echo $this->session->userdata('user')->name ?></p>
         <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
       </div>
     </div>
     <!-- search form -->
     <form action="#" method="get" class="sidebar-form hidden">
       <div class="input-group">
         <input type="text" name="q" class="form-control" placeholder="Search...">
         <span class="input-group-btn">
           <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
           </button>
         </span>
       </div>
     </form>
     <!-- /.search form -->
     <!-- sidebar menu: : style can be found in sidebar.less -->
     <ul class="sidebar-menu" data-widget="tree">
       <li class="header">MAIN NAVIGATION</li>
       <li>
         <a href="dashboard"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
       </li>
       <li>
         <a href="admin/earnings">
           <i class="fa fa-image"></i> <span>Earnings</span>
         </a>
       </li>
       <li>
         <a href="admin/expenses">
           <i class="fa fa-image"></i> <span>Expenses</span>
         </a>
       </li>
       <li>
         <a href="orders">
           <i class="fa fa-image"></i> <span>Orders</span>
         </a>
       </li>
       <li>
         <a href="users"><i class="fa fa-th"></i> <span>Staff</span></a>
       </li>

       <li>
         <a href="tables">
           <i class="fa fa-th"></i> <span>Tables</span>
         </a>
       </li>
       <li>
         <a href="categories">
           <i class="fa fa-th"></i> <span>Categories</span>
         </a>
       </li>
       <li>
         <a href="dishes">
           <i class="fa fa-th"></i> <span>Dishes</span>
         </a>
       </li>

       <li class="treeview">
         <a href="#">
           <i class="fa fa-th"></i>
           <span>Settings</span>
         </a>
         <ul class="treeview-menu">
           <li>
             <a href="background/add">
               <i class="fa fa-image"></i> <span>Add Background Image</span>
             </a>
           </li>
           <li>
             <a href="settings/baseurl">
               <i class="fa fa-image"></i> <span>Base URL</span>
             </a>
           </li>
         </ul>
       <li>
     </ul>
   </section>
   <!-- /.sidebar -->
 </aside>
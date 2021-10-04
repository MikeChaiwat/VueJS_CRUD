<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VueJS CRUD</title>

  <!-- CSS Styles -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>
  <div class="container mt-5" id="crudApp">
    <br>
    <h3 align="center">CRUD App Using VueJS & PHP</h3>
    <hr>
    <br>
    <div class="row">
      <div class="col-md-6">
        <h3 class="panel-title">Users Data</h3>
      </div>
      <div class="col-md-6" align="right">
        <input type="button" class="btn btn-success btn-xs" data-bs-toggle="modal" data-bs-target="#getDataModal" @click="openModal" value="Add">
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Fisrt name</th>
            <th>Last name</th>
            <th>Email</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in allData">
            <td>{{ row.first_name }}</td>
            <td>{{ row.last_name }}</td>
            <td>{{ row.email }}</td>
            <td>
              <button type="button" name="edit" class="btn btn-primary btn-xs" data-bs-toggle="modal" data-bs-target="#getDataModal" @click="fetchData(row.id)">Edit</button>
              <button type="button" name="delete" class="btn btn-danger btn-xs" @click="deleteData(row.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  

    <div v-if="myModal" class="modal fade" id="getDataModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ dynamicTitle }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="myModal=false" ></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="firstName">First Name</label>
              <input type="text" v-model="first_name" class="form-control">
            </div>
            <div class="form-group">
              <label for="lastName">Last Name</label>
              <input type="text" v-model="last_name" class="form-control">
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" v-model="email" class="form-control">
            </div>
            <br>
            <div class="modal-footer">
              <input type="hidden" v-model="hiddenId">
              <input type="button" v-model="actionButton" @click="submitData" class="btn btn-success btn-xs" >
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

  <script>
    let app = new Vue({
      el: '#crudApp',
      data: {
        allData: '',
        myModal: 'false',
        hiddenId: null,
        actionButton: 'Insert',
        dynamicTitle: 'Add Data',
        first_name: '',
        last_name: '',
        email: '',
      },
      methods: {
        fetchAllData(){
          axios.post('action.php', {
            action: 'fetchall'
          }).then(res => {
            app.allData = res.data;
          })
        },
        openModal(){
          app.actionButton = 'Insert';
          app.dynamicTitle = 'Add Data';
          app.myModal = true;
        },
        submitData() {
          if(app.first_name != '' && app.last_name != '' && app.email != '') {
            if(app.actionButton == 'Insert') {
              axios.post('action.php', {
                action: 'insert',
                firstName: app.first_name,
                lastName: app.last_name,
                email: app.email
              }).then(res => {
                app.myModal = false;
                app.fetchAllData();
                app.first_name = '';
                app.last_name = '';
                app.email = '';
                alert(res.data.message);
                window.location.reload();
              })
            }
            if(app.actionButton == 'Update'){
              axios.post('action.php', {
                action: 'update',
                firstName: app.first_name,
                lastName: app.last_name,
                email: app.email,
                id: app.hiddenId
              }).then(res => {
                app.myModal = 'false';
                app.fetchAllData();
                app.first_name = '';
                app.last_name = '';
                app.email = '';
                app.hiddenId = '';
                alert(res.data.message);
                window.location.reload();
              })
            }
          }
        },
        fetchData(id) {
          axios.post('action.php', {
            action: 'fetchData',
            id: id
          }).then(res => {
            app.first_name = res.data.first_name;
            app.last_name = res.data.last_name;
            app.email = res.data.email;
            app.hiddenId = res.data.id;
            app.myModal = true;
            app.actionButton = 'Update';
            app.dynamicTitle = 'Edit Data';
          })
        },
        deleteData(id) {
          if(confirm("Are you sure you want to remove this data?")) {
      
            axios.post('action.php', {
              action: 'delete',
              id: id
            }).then(res => {
              app.fetchAllData();
              alert(res.data.message);
            })
          }
        }
      },
      created() {
        this.fetchAllData();
      }
    })
  </script>
</body>
</html>
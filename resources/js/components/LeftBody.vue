<template>
  <div id="left">
    <div class="profile">
      <h1>Hi, Welcome to BFF Todo</h1>
      <h2>Let's create some works</h2>
    </div>
    <div class="search">
      <input
        v-model="search"
        class="form-group"
        placeholder="Search your project..."
      />
      <button class="btn btn-primary">
        <i class='bx-fw bx bx-search bx-tada' ></i>
      </button>
    </div>

   
    
        <div class="add-project">
          <input
            v-model="Projectname"
            class="form-group"
            placeholder="Add new project..."
          />

          
          <button
          @click="AddNewProject()"
          style="float: right"
          class="btn btn-primary"
          :disabled="active"
        >
          <div
            v-if="loading"
            class="spinner-border spinner-border-md"
            style="width: 1.5rem; height: 1.5rem"
          ></div>
          <i v-else class="bx-fw bx bx-plus-circle bx-tada"></i>
        </button>
        </div>
        <span class="error" v-if="msg.projectname">{{msg.projectname}}</span>

    <h2>Project</h2>
    <br />

    <div class="project">
      <div class="projects">
        <div
          v-for="(project, index) in Projects"
          :key="project.id"
          class="a-project"
          @click="SelectedProject(project)"
        >
          <div class="box-color">
            <div
              :class="
                project.id == selected
                  ? 'card text-white mb-3 selected'
                  : 'card text-white mb-3'
              "
            >
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="icon">
                      <i class='bx-fw bx bx-notepad bx-tada' ></i>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <p
                    v-if="editingId == project.id"
                     class="card-text">
                     <input placeholder="Change task name..."
                  type="text"
                  v-model="project.title"
                  :id="`project-name-${project.id}`"
                  @blur="UpdateProject(project)"
                  @keydown.enter="UpdateProject(project)">
                     </p>
                   
                <p v-else @click="SetToEditingProject(project)">{{ project.title }}</p>
                  </div>
                </div>
                    
                 
                
                
                <span
                  @click="DeleteProject(project.id, index)"
                  class="
                    position-absolute
                    top-0
                    start-100
                    translate-middle
                    badge
                    rounded-pill
                    bg-danger
                  "
                >
                  X
                  <span class="visually-hidden">unread messages</span>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    Project: {
      type: Array,
      default: [],
    },
  },
  data() {
    return {
      editingId: '',
      Projects: this.Project,
      selected: '',
      Projectname: "",
      loading: false,
      search: "",
      msg: [],
      active: false
    };
  },
  watch: {
    Project(newValue) {
      this.Projects = newValue;
    },
    search() {
      this.SearchProject(this.search);
    },
    Projectname() {
      console.log(this.Projectname.length);
      if(this.Projectname.length > 40){
        this.msg['projectname'] = 'The name of the project must not exceed 40 characters';
        this.active = true;
      }
      else{
        this.msg['projectname'] = '';
        this.active = false;
      }
    }
  },
 
  methods: {
    UpdateProject(project){
      const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener("mouseenter", Swal.stopTimer);
          toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
      });
      const name = {
        title: project.title
      }
      fetch(`/api/updateproject/${project.id}`,{
        method: "put",
        headers:{
          "content-type":"application/json"
        },
        body: JSON.stringify(name)
      }).then(()=> {
        Toast.fire({
                icon: "success",
                title: "Change project name successfully",
              });
        this.editingId = '';
      }).catch((err) => {
        Toast.fire({
                icon: "error",
                title: "Opps! something went wrong",
              });
      });
    },
    SetToEditingProject(project){
      this.editingId = project.id;
      setTimeout(()=> {
        document.getElementById(`project-name-${project.id}`).focus();
      },1)
    },
    SearchProject(keyword) {
      fetch(`api/project/${keyword}`)
        .then((res) => res.json())
        .then(({ data }) => {
          this.Projects = data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    DeleteProject(project_id, index) {
      const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener("mouseenter", Swal.stopTimer);
          toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
      });

      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`/api/project/${project_id}`, {
            method: "delete",
          })
            .then((res) => res.json())
            .then(() => {
              this.Projects.splice(index, 1);
            })
            .catch((err) => {
              Toast.fire({
                icon: "error",
                title: "Opps! something went wrong!",
              });
            });
          Toast.fire({
            icon: "success",
            title: "Your project has been deleted",
          });
        }
      });
    },
    SelectedProject(project) {
      this.selected = project.id;
      this.$emit("selected", project);
    },

    async AddNewProject() {
      
         this.loading = !false;
      const project = {
        title: this.Projectname
      };
        const res = await fetch("/api/insertproject", {
          method: "post",
          headers: {
            "content-type": "application/json",
          },
          body: JSON.stringify(project),
        });
        const data = await res.json();
        setTimeout(()=> {
          
          this.$props.Project.push(data.data);
          this.Projectname = "";
          this.loading = !true;
        }, 2000);

       

      
    },
  },
};
</script>

<style>
</style>
<template>
  <div id="right">
    <div class="title-icon">
      <h1>BFF Todo <i class='bx-fw bx bx-task bx-tada' ></i></h1>
    </div>
    

    <div class="horizontal"></div>
    <p>“To do list” is how you list, implement and monitor yourself to accomplish your work goals effectively and on schedule.</p>
    <div class="task">
      <div class="add-tasks">
        <h3>Today's task</h3>
        <div class="add-action">
           <h5><i class='bx-fw bx bxs-info-circle bx-tada' ></i> You can edit task by click on name or click on button</h5>
        </div>
      </div>
      <div v-if="TodayTsk" class="task-lisk">
        <div v-for="dailytsk in TodayTsk" v-bind:key="dailytsk.id">
          <div class="info">
            <div class="row">
              <div class="col-md-8" style="display: flex">
                <label class="myCheckbox">
                  <input
                    class="form-check-input me-1"
                    type="checkbox"
                    :checked="dailytsk.completed"
                    @change="CheckDailyTask(dailytsk.taskId)"
                  />

                  <span></span>
                </label>
                <h4 v-if="editingIddaily == dailytsk.id">
                  <input placeholder="Change task name..."
                  type="text"
                  :checked="activecheck"
                  v-model="dailytsk.title"
                  :id="`task-name-daily-${dailytsk.id}`"
                  @blur="UpdateDailyTask(dailytsk)"
                  @keydown.enter="UpdateDailyTask(dailytsk)">
                </h4>
                <h4 v-else @click="SetToEditingDaily(dailytsk)">{{ dailytsk.title }}</h4>
              </div>
              <div class="col-md-4">
                <button  @click="SetToEditingDaily(dailytsk)" class="btn btn-warning" ><i class='bx-fw bx bxs-edit' ></i></button>
                <button
                  class="btn btn-danger"
                  @click="DeleteDailyTask(dailytsk.taskId)"
                >
                <i class='bx-fw bx bxs-trash-alt' ></i>
                </button>
                <button class="btn btn-light">Pending</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="upcoming">
      <div class="add-tasks">
       <h3>Upcoming Task</h3>
        <div class="add-action">
          
        </div>
      </div>
      <form action="" @submit="AddUpcomingTask">
        <input
          :disabled="active"
          placeholder="Add some upcoming task..."
          type="text"
          v-model="NewTask"
        />
      </form>
     
      <div v-if="UpcomingTask" class="tasks-list">
        <div v-for="(upcomingtsk, index) in UpcomingTask" v-bind:key="upcomingtsk.id">
          <div class="info">
            <div class="row">
              <div class="col-md-8" style="display: flex">
                <label class="myCheckbox">
                  <input
                    class="form-check-input me-1"
                    type="checkbox"
                    :checked="upcomingtsk.completed"
                    @change="CheckUpcomingTask(upcomingtsk.taskId, index)"
                  />

                  <span></span>
                </label>
                
                <h4 v-if="editingId == upcomingtsk.id">
                  <input placeholder="Change task name..."
                  type="text"
                  v-model="upcomingtsk.title"
                  :id="`task-name-${upcomingtsk.id}`"
                  @blur="UpdateUpcomingTask(upcomingtsk)"
                  @keydown.enter="UpdateUpcomingTask(upcomingtsk)">
                </h4>
                <h4 v-else @click="SetToEditing(upcomingtsk)">{{ upcomingtsk.title }}</h4>
              </div>
              <div class="col-md-4">
                <button @click="SetToEditing(upcomingtsk)" class="btn btn-warning" ><i class='bx-fw bx bxs-edit' ></i></button>
                <button
                  class="btn btn-danger"
                  @click="DeleteUpcomingTask(upcomingtsk.taskId)"
                >
                <i class='bx-fw bx bxs-trash-alt' ></i>
                </button>
                <button class="btn btn-light">Waiting</button>
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
   props:{
    Upcoming: {
      type: Array,
      default: []
    },
     TodayTask: {
      type: Array,
      default: []
    },
    SelectedProject: {
      type: Number,
      default: null
    }
  },
  watch:{
    Upcoming (newValue){
      this.UpcomingTask = newValue;
    },
    TodayTask (newValue){
      this.TodayTsk = newValue;
    },
  },
  data() {
    return {
      UpcomingTask: this.Upcoming,
      TodayTsk: this.TodayTask,
      NewTask: "",
      loading: false,
      editingId: '',
      editingIddaily: '',
      selectedproject: null,
      active: false,
      activecheck: false
    };
  },
  
  methods: {
    
    SetToEditing(upcomingtsk){
      this.editingId = upcomingtsk.id;
  
      setTimeout(() => {
        document.getElementById(`task-name-${upcomingtsk.id}`).focus();
      },1);

    },
    SetToEditingDaily(dailytsk){
      this.editingIddaily = dailytsk.id;
      setTimeout(() => {
         document.getElementById(`task-name-daily-${dailytsk.id}`).focus();
      },1);
      

    },
    UpdateDailyTask(dailytsk){
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

       const newName = {
        title: dailytsk.title
      };

      fetch(`/api/updatedailytask/${dailytsk.taskId}`, {
        method: 'post',
        headers: {
          "content-type" : "application/json"
        },
        body: JSON.stringify(newName)
      })
      .then(() => {
        Toast.fire({
                icon: "success",
                title: "Change task name successfully",
              });
        this.editingIddaily = '';
      })
      .catch((err) => {
        Toast.fire({
                icon: "error",
                title: "Opps! something went wrong",
              });
      });
      
    },
    UpdateUpcomingTask(upcomingtsk){
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
      const newName = {
        title: upcomingtsk.title
      };
      fetch(`/api/editupcomingtask/${upcomingtsk.id}`,{
        "method" : "POST",
        headers:{
          "content-type": "application/json"
        },
        body: JSON.stringify(newName)
      }).then(() => {
        Toast.fire({
                icon: "success",
                title: "Change task name successfully",
              });
        this.editingId = '';
      }).catch((err) => {
        Toast.fire({
                icon: "error",
                title: "Opps! something went wrong!",
              });
      });
      
    },
    
    //add upcoming task
    AddUpcomingTask(e) {
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
      e.preventDefault();
      if(this.NewTask == "") {
        Toast.fire({
          icon: "error",
          title: "You need to typing task name first",
        });
      }
      if(this.$props.SelectedProject == null){
        Toast.fire({
          icon: "error",
          title: "Please select project first",
        });
      }
      else
      {
        if (this.Upcoming.length > 4) {
        Toast.fire({
          icon: "warning",
          title: "You need to complete some upcoming task first",
        });
      } else {
        const newTsk = {
          title: this.NewTask,
          waiting: true,
          taskId: Math.random().toString(36).substring(7),
          project_id:  this.$props.SelectedProject
        };
        //create post request
        fetch("/api/upcoming", {
          method: "POST",
          headers: {
            "content-type": "application/json",
          },
          body: JSON.stringify(newTsk),
        }).then(() =>this.UpcomingTask.push(newTsk));
        //clear or reset data new task
        this.NewTask = "";
      }
      }
      
    },
    DeleteUpcomingTask(taskId) {
    
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
          fetch(`/api/upcoming/${taskId}`, {
            method: "delete",
          })
            .then((res) => res.json())
            .then(() => {
              this.UpcomingTask = this.UpcomingTask.filter(
                ({ taskId: id }) => id !== taskId
              );
              
            })
            .catch((err) => {
              Toast.fire({
                icon: "error",
                title: "Opps! something went wrong!",
              });
            });
          Toast.fire({
            icon: "success",
            title: "Your task has been deleted",
          });
        }
      });
    },
    CheckUpcomingTask(taskId, index) {
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
      if (this.TodayTask.length > 4) {
        Toast.fire({
          icon: "warning",
          title: "You need to complete today task first",
        });
      }
      else {
        this.AddToDailyTask(taskId);

        //remove from Upcoming task
        fetch(`/api/upcoming/${taskId}`, {method: "delete"}).then(()=>{
          this.UpcomingTask.splice(index, 1);
        });
      }
    },
    AddToDailyTask(taskId){
      const task = this.UpcomingTask.filter(({taskId : id}) => id == taskId)[0];
      //Post request
      fetch("/api/dailytask",{
        method: "post",
        headers: {
          "content-type" : "application/json"
        },
        body: JSON.stringify(task)
      }).then(() => {
        this.TodayTsk.push(task);
      }).catch((err)=> {
        console.log(err);
      });
    },
    DeleteDailyTask(taskId){
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
          fetch(`/api/dailytask/${taskId}`, {
            method: "delete",
          })
            .then((res) => res.json())
            .then(() => {
              this.TodayTsk = this.TodayTsk.filter(
                ({taskId: id}) => id !== taskId
              );
            })
            .catch((err) => {
              Toast.fire({
                icon: "error",
                title: "Opps! something went wrong!",
              });
            });
          Toast.fire({
            icon: "success",
            title: "Your task has been deleted",
          });
        }
      });
    },
    CheckDailyTask(taskId) {
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
        confirmButtonText: "Yes, complete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`/api/dailytask/${taskId}`, {
            method: "delete",
          })
            .then((res) => res.json())
            .then(() => {
              this.TodayTsk = this.TodayTsk.filter(
                ({taskId: id}) => id !== taskId
              );
            })
            .catch((err) => {
              Toast.fire({
                icon: "error",
                title: "Opps! something went wrong!",
              });
            });
          Toast.fire({
            icon: "success",
            title: "Your task has been completed",
          });
        }
        else if(result.dismiss)
          {
            this.activecheck = false;
        }
      });
    }
  },
};
</script>

<style>
</style>
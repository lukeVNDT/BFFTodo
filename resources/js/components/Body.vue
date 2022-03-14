<template>
  <div id="body">
      <LeftBody :Project="Project" @selected="ProjectById"/>
      <RightBody :SelectedProject="SelectedProject" :TodayTask="TodayTask" :Upcoming="Upcoming"/>
  </div>
</template>

<script>
import LeftBody from './LeftBody.vue';
import RightBody from './RightBody.vue';
export default {
    data(){
        return{
            SelectedProject: null,
            Project: [],
            Upcoming:  [],
            TodayTask: [],
        }
    },
    mounted(){
        this.GetAllProject();
    },
    methods:{
       
        GetAllProject(){
            fetch("/api/project")
            .then((res) => res.json())
            .then(({data}) => {
                this.Project = data;
                })
            .catch((err) => {
                console.log(err)
                });
        },
        ProjectById(project){

           //get upcoming task by project
           fetch(`/api/upcomingbyid/${project.id}`)
           .then((res) => res.json())
           .then(({data}) => {
           this.Upcoming = data;
           this.SelectedProject = project.id;
           }).catch((err)=>{
               console.log(err);
           });

           //get daily task by project
           fetch(`/api/dailytaskbyid/${project.id}`)
           .then((res) => res.json())
           .then(({data}) => {
               this.TodayTask = data;
           })
           .catch((err)=>{
               console.log(err);
           })
        },
        
    },
    components: {
        LeftBody,
        RightBody,
    }
}
</script>

<style>

</style>
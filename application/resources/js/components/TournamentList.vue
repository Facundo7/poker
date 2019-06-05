<template>
    <div class="container">
        <div class="row justify-content-center">
            <table class="table table-bordered">
                <tr><th>Title</th><th>BB/SB</th><th>Initial stack</th><th>Buy in</th><th>players</th><th>join</th></tr>
                <tr v-for="tournament in tournaments" v-bind:key="tournament.id">
                    <td>{{tournament.title}}</td>
                    <td>{{tournament.bb_start_value}} / {{tournament.bb_start_value / 2}}</td>
                    <td>{{tournament.initial_stack}}</td>
                    <td>{{tournament.buy_in}}</td>
                    <td>{{tournament.players_count}}/{{tournament.players_number}}</td>
                    <td><button class="btn join-btn" @click="join(tournament.id)">Join</button></td>
                </tr>
            </table>
            <button class="btn create-btn" @click="newTournament() ">New Tournament</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "tournamentlist",
        data: function(){
            return {
                tournaments:[],
            }
        },
        mounted() {
            this.getData();
        },
        methods: {
            getData(){
                axios.get(route('api.tournaments.index')).then(response => {
                this.tournaments = response.data;
                });
            },
            join(id){
                window.location.href=(route('tournaments.show',id));
            },
            newTournament(){

                window.location.href=(route('tournaments.create'));

            }
        }
    }
</script>

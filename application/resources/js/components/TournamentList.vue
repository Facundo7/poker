<template>
    <div class="container">
        <div class="row">
            <h1 class="title">Tournaments</h1>
            <table class="table table-dark table-hover">
                <thead class="thead-light">
                <tr><th style="width: 40%">Title</th><th>BB/SB</th><th>Initial stack</th><th>Buy in</th><th>Players</th></tr>
                </thead>
                <tbody>
                <tr v-for="tournament in tournaments" v-bind:key="tournament.id" @click="join(tournament.id)">
                    <td>{{tournament.title}}</td>
                    <td>{{tournament.bb_start_value}} / {{tournament.bb_start_value / 2}}</td>
                    <td>{{tournament.initial_stack}}</td>
                    <td>{{tournament.buy_in}}</td>
                    <td>{{tournament.players_count}}/{{tournament.players_number}}</td>

                </tr>
                </tbody>
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

<template>
    <div class="container">

        <div class="poker-table" :class="sitsClass">
            <div class="sit"></div>
            <div class="table-info"></div>
            <div class="cards"></div>
            <div class="user-panel"></div>
        </div>


        <div class="row">
            <ul>
                <li v-for="player in players" v-bind:key="player.id">
                    {{player.nickname}}
                </li>
            </ul>
        </div>
        <div class="row">
            <button @click="sit(tournament_id)">Join</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "game",
        props: ['tournament_id'],
        data: function(){
            return {
                players:[],
                player:[],
                table_cards:[],
                player_cards:[],
                number_of_sits: 5,
                pot:[],
                sitsClass: null
            }
        },
        mounted() {
            this.getData();
            this.setClass();
        },
        methods: {
            getData(){
                axios.get(route('api.players.index',{tournamentid: this.tournament_id})).then(response => {
                    console.log("all right");
                this.players = response.data;
                });
            },
            check(){

            },
            fold(){

            },
            bet(amount){

            },
            sit(id){
                var self = this;
                axios.post(route('api.players.store'), {
                tournament_id: this.tournament_id,
                })
                .then(function (response) {
                console.log(response);
                self.getData();
                });
            },
            setClass(){
                switch(this.number_of_sits) {
                    case 3:
                        this.sitsClass="sits3";
                        break;
                    case 4:
                        this.sitsClass="sits4";
                        break;
                    case 5:
                        this.sitsClass="sits5";
                        break;
                    case 6:
                        this.sitsClass="sits6";
                        break;
                    case 7:
                        this.sitsClass="sits7";
                        break;
                    case 8:
                        this.sitsClass="sits8";
                        break;
                    case 9:
                        this.sitsClass="sits9";
                        break;
                    }
            },
        }
    }
</script>

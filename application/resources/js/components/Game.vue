<template>
    <div class="container">

        <div class="poker-table" :class="sitsClass">
            <template v-if="players.length>0">
            <div v-for="index in tournament.players_number" :key="index" class="sit">{{players[index-1] ? players[index-1].nickname : 'empty'}}</div>
            <div class="table-info"></div>
            <div class="cards"></div>
            <div class="user-panel"></div>
            </template>
        </div>


        <div class="row">
            <ul>
                <li v-for="player in players" :key="player.id">
                    {{player.nickname}}
                </li>
            </ul>
        </div>
        <div class="row">
            <button @click="sit(tournament_id)">Join</button>
            <button @click="orderArray()">order</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "game",
        props: ['tournament_id'],
        data: function(){
            return {
                players:[], //nice
                player:'asdf', //nice
                tournament:null, //nice
                round:null,//---
                bet_round:null,//---
                table_cards:[], //---
                player_cards:[], //---
                pot:[],//---
                sitsClass: null,//nice
                status: 'stop'//---
            }
        },
        mounted() {
            this.getData();
            this.setClass();
        },
        methods: {
            getData(){
                var self=this;
                axios.get(route('api.tournaments.show',{tournament: this.tournament_id})).then(response => {
                    this.tournament = response.data;
                    console.log("get tournament done");
                    axios.get(route('api.players.index',{tournamentid: this.tournament_id})).then(response => {
                        this.players = response.data;
                        console.log("get players done");
                        axios.get(route('api.players.logged',{tournament_id: this.tournament_id})).then(response => {
                            this.player = response.data;
                            console.log("get player logged done");
                            if(this.player!=''){
                                self.orderArray();
                            }
                        });
                    });
                });


            },
            getAllPlayers(){
                var self=this;
                axios.get(route('api.players.index',{tournamentid: this.tournament_id})).then(response => {
                    this.players = response.data;
                    console.log("get players done");
                });
            },
            getPlayer(){
                var self=this;
                axios.get(route('api.players.logged',{tournament_id: this.tournament_id})).then(response => {
                    this.player = response.data;
                    console.log("get player logged done");
                });
            },
            getTournament(){
                var self=this;
                axios.get(route('api.tournaments.show',{tournament: this.tournament_id})).then(response => {
                    this.tournament = response.data;
                    console.log("get tournament done");
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
            orderArray(){
                console.log('starting order');
                if(this.player!=null){
                    var z=0;
                    var array=[];
                    for(var i=this.player.sit;i<=this.players.length;i++){
                        for(var x=0;x<this.players.length;x++){
                            if(this.players[x].sit==i){
                            Vue.set(array, z, this.players[x]);
                            break;
                            }
                        }
                        z++;
                    }
                    for(var i=1;i<this.player.sit;i++){
                        for(var x=0;x<this.players.length;x++){
                            if(this.players[x].sit==i){
                            Vue.set(array, z, this.players[x]);
                            break;
                            }
                        }
                        z++;
                    }
                    this.players=array;
                }
            }
        }
    }
</script>

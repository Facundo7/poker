<template>
    <div class="container">

        <div class="poker-table" :class="sitsClass">
          <template v-if="players_show">
            <div v-for="index in players_show.length" :key="index" class="sit">
                {{players_show[index-1].user.nickname}}
                {{players_show[index-1].stack-players_show[index-1].betting}}
                {{players_show[index-1].betting}}
            <div class="cards">
            </div>
            </div>
            <div class="table-infor">
                <br>my cards<br>
                {{player.cards[0].card.value}} of {{player.cards[0].card.suit}}<br>
                {{player.cards[1].card.value}} of {{player.cards[1].card.suit}}
                <br>
                <br>
                board cards <br>
                <span v-for="index in round.board_cards.length" :key="index">
                    {{board_cards[index-1]}}<br>
                </span>
            </div>
            <div class="user-panel">
                <input type="text" v-model="amount">
                <button @click="act(0,amount)">Check</button>
                <button @click="act(1,amount)">call</button>
                <button @click="act(2,amount)">raise</button>
                <button @click="act(3,amount)">reraise</button>
                <button @click="act(4,amount)">Fold</button>
            </div>
            </template>
        </div>


        <div class="row">
            <ul>
                <li v-for="player in players" :key="player.id">
                    {{player.user.nickname}}
                </li>
            </ul>
        </div>
        <div class="row">
            <button @click="sit(tournament_id)">Join</button>
            <button @click="orderArray()">order</button>
            <button @click="getData()">order</button>
        </div>
        <div class="row">
            <ul>
                <li>players: {{players}}</li>
                <li>players_show: {{players_show}}</li>
                <li>player: {{player}}</li>
                <li>tournament: {{tournament}}</li>
                <li>round: {{round}}</li>
                <li>betround: {{bet_round}}</li>
                <li>board cards: {{board_cards}}</li>
                <li>player cards:  {{player_cards}}</li>
                <li>pot: {{pot}}</li>
                <li>amount: {{amount}}</li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: "game",
        props: ['tournament_id'],
        data: function(){
            return {
                players:'', //nice
                player:'', //nice
                tournament:'', //nice
                round:'',//nice
                bet_round:'',//nice
                amount:15,
                sitsClass: '',//nice
                status: 'stop',//---?????
            }
        },
        computed: {
            pot: function() {
                return this.round.pot;
            },
            player_cards: function() {
                return this.player.cards;
            },
            board_cards: function() {
                return this.round.board_cards;
            },
            players_show: function(){
                 if(this.player!='' && this.players!=''){
                     return this.orderArray(this.player, this.players);
            };
            }

        },
        mounted() {
            this.getData();
            this.listen();
            this.setClass();
        },
        methods: {
            getData(){
                this.getTournament();
                this.getAllPlayers();
                this.getPlayer();
                this.getRound();
                this.getBetRound();

            },
            getAllPlayers(){
                var self=this;
                axios.get(route('api.tournaments.players',{tournament: this.tournament_id})).then(response => {
                    this.players = response.data;
                    console.log("get players done");
                });
            },
            getPlayer(){
                var self=this;
                axios.get(route('api.tournaments.playerlogged',{tournament: this.tournament_id})).then(response => {
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
            getRound(){
                var self=this;
                axios.get(route('api.tournaments.round',{tournament: this.tournament_id})).then(response => {
                    this.round = response.data;
                    console.log("get round done");
                });
            },
            getBetRound(){
                var self=this;
                axios.get(route('api.tournaments.betround',{tournament: this.tournament_id})).then(response => {
                    this.bet_round = response.data;
                    console.log("get betRound done");
                });
            },
            act(action, amount){
                axios.post(route('api.actions.store'), {
                action: action,
                bet_round_id: this.bet_round.id,
                player_id: this.player.id,
                amount: amount,

                })
                .then(function (response) {
                console.log("action done");
                });
            },
            sit(id){
                var self = this;
                axios.post(route('api.players.store'), {
                tournament_id: this.tournament_id,
                })
                .then(function (response) {
                console.log(response);
                //self.getData();
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
            orderArray(player, players){
                console.log('starting order');

                    var z=0;
                    var array=[];
                    for(var i=player.sit;i<=players.length;i++){
                        for(var x=0;x<players.length;x++){
                            if(players[x].sit==i){
                            Vue.set(array, z, players[x]);
                            break;
                            }
                        }
                        z++;
                    }
                    for(var i=1;i<player.sit;i++){
                        for(var x=0;x<players.length;x++){
                            if(players[x].sit==i){
                            Vue.set(array, z, players[x]);
                            break;
                            }
                        }
                        z++;
                    }
                    return array;

            },
            listen(){
                Echo.channel('tournament.'+this.tournament_id)
                .listen('NewPlayer', ()=>{this.getData()})
                .listen('NewBetRound', ()=>{this.getData()})
                .listen('NewRound', ()=>{this.getData()})
                .listen('NewAction', ()=>{this.getData()});

            }
        }
    }
</script>

<template>
    <div class="container-fluid">
        <div class="poker-interface">
            <div class="poker-table">
                <template v-if="player && players_show">
                <div :class="sitsClass">
                    <div v-for="player in players_show" :key="player.id" class="player-info sit">
                        <div class="text-center">{{player.user.nickname}}</div>
                        <div class="text-center">{{player.stack-player.betting}}</div>
                        <div class="text-center">{{player.betting}}</div>
                        <div class="player-icons">
                            <div v-if="player.playing" class="mini-card1"></div>
                            <div v-if="player.playing" class="mini-card2"></div>
                            <div v-if="player.button" class="button"><span class="align-middle">B</span></div>
                            <div v-if="player.sb" class="sb"><span class="align-middle">SB</span></div>
                            <div v-if="player.bb" class="bb"><span class="align-middle">BB</span></div>
                        </div>
                    </div>
                </div>

                <div class="round-info">POT: {{round.pot}}</div>
               <div v-if="board_cards" class="board-cards d-flex justify-content-around">
                    <div v-if="board_cards[0]" class="poker-card">{{values[board_cards[0].card.value-2]}} {{icons[board_cards[0].card.suit-1]}}</div>
                    <div v-if="board_cards[1]" class="poker-card">{{values[board_cards[1].card.value-2]}} {{icons[board_cards[1].card.suit-1]}}</div>
                    <div v-if="board_cards[2]" class="poker-card">{{values[board_cards[2].card.value-2]}} {{icons[board_cards[2].card.suit-1]}}</div>
                    <div v-if="board_cards[3]" class="poker-card">{{values[board_cards[3].card.value-2]}} {{icons[board_cards[3].card.suit-1]}}</div>
                    <div v-if="board_cards[4]" class="poker-card">{{values[board_cards[4].card.value-2]}} {{icons[board_cards[4].card.suit-1]}}</div>
                </div>
                <div class="player-cards d-flex justify-content-around">
                    <div class="poker-card">{{values[player_cards[0].card.value-2]}} {{icons[player_cards[0].card.suit-1]}}</div>
                    <div class="poker-card">{{values[player_cards[1].card.value-2]}} {{icons[player_cards[1].card.suit-1]}}</div>
                </div>
                <div v-if="player.id==bet_round.turn" class="user-panel">
                    <div class="pot-buttons">
                        <button class="btn pot-button">1/3</button>
                        <button class="btn pot-button">1/2</button>
                        <button class="btn pot-button">2/3</button>
                        <button class="btn pot-button">POT</button>
                        <button class="btn pot-button">All in</button>
                    </div>
                    <div class="amount">
                        <input type="range" class="range-input" v-bind:min="max_bet-player.betting+minimum_bet" v-bind:max="player.stack-player.betting" v-model="amount">
                        <input type="text" class="amount-input" v-model="amount">
                    </div>
                    <div class="buttons">
                        <button v-if="max_bet==player.betting" @click="act(0,0)" class="btn act-button">Check</button>
                        <button v-else @click="act(1,max_bet-player.betting)" class="btn act-button">Call({{max_bet-player.betting}})</button>
                        <button v-if="max_bet==0" @click="act(2,amount)" class="btn act-button">Bet({{amount}})</button>
                        <button v-else @click="act(3,amount)" class="btn act-button">Raise({{amount}})</button>
                        <button @click="act(4,0)" class="btn act-button">Fold</button>
                    </div>
                </div>
                </template>
            </div>

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
                <li>max: {{max_bet}}</li>
                <li>bb: {{round.bb}}</li>
                <li>min: {{minimum_bet}}</li>
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
                amount:'',
                sitsClass:'xd',//nice
                values:[2,3,4,5,6,7,8,9,10,'J','Q','K','A'],
                icons:['♠','♥','♣','◆'],

            }
        },
        computed: {
            pot: function() {
                return this.round.pot;
            },
            max_bet: function() {
                var max=0;
                for(var i=0;i<this.players.length;i++) {
                    if (this.players[i].betting>max){
                        max=this.players[i].betting;
                    }
                }
                return max;
            },
            minimum_bet: function() {

                var max=0;
                var second=0;

                 for(var i=0;i<this.players.length;i++) {
                    if (this.players[i].betting>max){
                        second=max;
                        max=this.players[i].betting;
                    }else{
                        if(this.players[i].betting>second){
                            second=this.players[i].betting;
                        }
                    }
                };

                if(max-second>this.round.bb){
                    return max-second;
                }else {
                    return this.round.bb;
                }
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
                    this.setClass();
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

                this.sitsClass='sits'+this.tournament.players_number;
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

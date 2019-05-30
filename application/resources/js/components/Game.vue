<template>
    <div class="container-fluid">
        <div class="poker-interface">
            <div class="poker-table">
                <template v-if="player && players_show">
                <div :class="sitsClass">
                    <div v-for="player in players_show" :key="player.id" class="player-info sit">
                        <div class="text-center">{{player.user.nickname}}</div>
                        <div class="text-center">{{player.stack-player.betting}}</div>
                        <div class="text-center">{{player.betting}}<i class="fas fa-coins"></i></div>
                        <div class="player-icons">
                            <div v-if="player.playing" class="mini-card1"></div>
                            <div v-if="player.playing" class="mini-card2"></div>
                            <div v-if="player.button" class="button"><span class="align-middle">B</span></div>
                            <div v-if="player.sb" class="sb"><span class="align-middle">SB</span></div>
                            <div v-if="player.bb" class="bb"><span class="align-middle">BB</span></div>
                        </div>
                    </div>
                </div>

                <div class="round-info">POT: {{round.pot}}<br>TOTAL POT: {{pot}}</div>
                <div v-if="board_cards" class="board-cards d-flex">
                    <div v-if="board_cards[0]" class="poker-card">
                        <div class="card-value">{{values[board_cards[0].card.value-2]}}</div>
                        <div class="card-suit" :style="{color:colors[board_cards[0].card.suit-1]}">{{icons[board_cards[0].card.suit-1]}}</div>
                    </div>
                    <div v-if="board_cards[1]" class="poker-card">
                        <div class="card-value">{{values[board_cards[1].card.value-2]}}</div>
                        <div class="card-suit" :style="{color:colors[board_cards[1].card.suit-1]}">{{icons[board_cards[1].card.suit-1]}}</div>
                    </div>
                    <div v-if="board_cards[2]" class="poker-card">
                        <div class="card-value">{{values[board_cards[2].card.value-2]}}</div>
                        <div class="card-suit" :style="{color:colors[board_cards[2].card.suit-1]}">{{icons[board_cards[2].card.suit-1]}}</div>
                    </div>
                    <div v-if="board_cards[3]" class="poker-card">
                        <div class="card-value">{{values[board_cards[3].card.value-2]}}</div>
                        <div class="card-suit" :style="{color:colors[board_cards[3].card.suit-1]}">{{icons[board_cards[3].card.suit-1]}}</div>
                    </div>
                    <div v-if="board_cards[4]" class="poker-card">
                        <div class="card-value">{{values[board_cards[4].card.value-2]}}</div>
                        <div class="card-suit" :style="{color:colors[board_cards[4].card.suit-1]}">{{icons[board_cards[4].card.suit-1]}}</div>
                    </div>
                </div>
                <div class="player-cards d-flex justify-content-around">
                    <div class="poker-card">
                        <div class="card-value">{{values[player_cards[0].card.value-2]}}</div>
                        <div class="card-suit" :style="{color:colors[player_cards[0].card.suit-1]}">{{icons[player_cards[0].card.suit-1]}}</div>
                    </div>
                    <div class="poker-card">
                        <div class="card-value">{{values[player_cards[1].card.value-2]}}</div>
                        <div class="card-suit" :style="{color:colors[player_cards[1].card.suit-1]}">{{icons[player_cards[1].card.suit-1]}}</div>
                    </div>
                </div>
                <div v-if="player.id==bet_round.turn" class="user-panel">
                    <div class="pot-buttons">
                        <button class="btn pot-button" @click="changeAmount(1)">1/3</button>
                        <button class="btn pot-button" @click="changeAmount(2)">1/2</button>
                        <button class="btn pot-button" @click="changeAmount(3)">3/4</button>
                        <button class="btn pot-button" @click="changeAmount(4)">POT</button>
                        <button class="btn pot-button" @click="changeAmount(5)">All in</button>
                    </div>
                    <div class="amount">
                        <input type="range" class="range-input" @input="function(){input=amount}" v-bind:min="max_bet+minimum_bet" v-bind:max="player.stack-player.betting" v-model="amount">
                        <input type="text" class="amount-input" v-model="input" @keyup="setAmount()">
                    </div>
                    <div class="buttons">
                        <button v-if="max_bet==player.betting" @click="act(0,0)" class="btn act-button">Check</button>
                        <button v-else @click="act(1,max_bet-player.betting)" class="btn act-button">Call({{max_bet-player.betting}})</button>
                        <button v-if="max_bet==0" @click="act(2,amount)" class="btn act-button">Bet({{amount}})</button>
                        <button v-else @click="act(3,amount-player.betting)" class="btn act-button">Raise({{amount}})</button>
                        <button @click="act(4,0)" class="btn act-button">Fold</button>
                    </div>
                </div>
                </template>

            </div>
            <div class="history">
                <p v-for="(action, index) in actions" :key="index">{{action}}</p>
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
                colors:['black','red','green','blue'],
                input:'',
                actions:[],
                verbs:['checks', 'calls', 'bets', 'raises to', 'folds'],

            }
        },
        computed: {
            pot: function() {
                    var bets=0;
                for (var i=0; i<this.players.length; i++) {
                    bets+=this.players[i].betting;
                }

                return this.round.pot+bets;
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

                    axios.all([
                        this.getTournament(),
                        this.getAllPlayers(),
                        this.getPlayer(),
                        this.getRound(),
                        this.getBetRound()
                    ]).then(axios.spread((tourRes, allPlayerRes,playerRes,roundRes,betRoundRes) => {
                        this.player = playerRes.data;
                        this.round = roundRes.data;
                        this.tournament = tourRes.data;
                        this.bet_round = betRoundRes.data;
                        this.players = allPlayerRes.data;
                        this.setClass();
                        if(this.max_bet!=0){
                            this.amount=this.max_bet+this.minimum_bet;
                        }else {
                            this.amount=this.max_bet-this.player.betting+this.minimum_bet;
                        }
                        this.input=this.amount;
                    }))
            },

            getAllPlayers(){
                return axios.get(route('api.tournaments.players',{tournament: this.tournament_id}));
            },
            getPlayer(){
                return axios.get(route('api.tournaments.playerlogged',{tournament: this.tournament_id}));
            },
            getTournament(){

                return axios.get(route('api.tournaments.show',{tournament: this.tournament_id}));
            },
            getRound(){
                return axios.get(route('api.tournaments.round',{tournament: this.tournament_id}));
            },
            getBetRound(){

                return axios.get(route('api.tournaments.betround',{tournament: this.tournament_id}));
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
            changeAmount(option){
                switch (option) {
                    case 1:
                        //1/3
                        if (this.pot/3>=this.minimum_bet+this.max_bet){
                            this.amount=Math.round(this.pot/3);
                        }
                        break;
                    case 2:
                        //1/2
                        if (this.pot/2>=this.minimum_bet+this.max_bet){
                            this.amount=Math.round(this.pot/2);
                        }
                        break;
                    case 3:
                        //1/4
                        if (this.pot/4*3>=this.minimum_bet+this.max_bet){
                            this.amount=Math.round(this.pot/4*3);
                        }
                        break;
                    case 4:
                        //pot
                        if (this.pot>=this.minimum_bet+this.max_bet){
                            this.amount=this.pot;
                        }
                        break;
                    case 5:
                        this.amount=this.player.stack-this.player.betting;
                        break;

                    default:
                        break;
                }

                this.input=this.amount;

            },
            setAmount(){

                if(isNaN(this.input)){
                    this.amount=this.max_bet-this.player.betting+this.minimum_bet;
                }else if(this.input>this.player.stack-this.player.betting){
                    this.amount=this.player.stack-this.player.betting;
                }else if(this.input<this.max_bet-this.player.betting+this.minimum_bet){
                    this.amount=this.max_bet-this.player.betting+this.minimum_bet;
                }else {
                    this.amount=Math.round(this.input);
                }

            },
            addAction(data){

                var newRow=data.action.player.user.nickname+" "+this.verbs[data.action.action];

                if(data.action.amount>0){
                    newRow+=" "+data.action.amount;
                }

                this.actions.push(newRow);

            },
            addShow(players){



                var firstRow="";

                for(var i=0; i<5; i++){
                    firstRow+=this.values[this.board_cards[i].card.value-2]+
                              this.icons[this.board_cards[i].card.suit-1]+" ";
                }

                this.actions.push(firstRow);

                for (var i=0;i<players.length; i++) {

                    this.actions.push(
                        players[i].user.nickname+" "+
                        this.values[players[i].cards[0].card.value-2]+
                        this.icons[players[i].cards[0].card.suit-1]+" "+
                        this.values[players[i].cards[1].card.value-2]+
                        this.icons[players[i].cards[1].card.suit-1]
                    );

                }

            },
            listen(){
                Echo.channel('tournament.'+this.tournament_id)
                .listen('NewPlayer', ()=>{this.getData()})
                .listen('NewBetRound', ()=>{this.getData()})
                .listen('NewRound', ()=>{this.getData()})
                .listen('NewAction', (data)=>{
                    console.log(data);
                    this.addAction(data);
                    this.getData();
                })
                .listen('ShowDown', (data)=>{console.log(JSON.parse(data.players)); this.addShow(JSON.parse(data.players))});

            }
        }
    }
</script>

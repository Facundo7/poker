<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-2">{{sits[0]}}</div>
            <div class="col-2">{{sits[1]}}</div>
            <div class="col-2">{{sits[2]}}</div>
            <div class="col-2">{{sits[3]}}</div>
            <div class="col-2">{{sits[4]}}</div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "tablexd",
        data: function() {
            return {
                sits: [],
                user: 1,
                length: 5
            }
        },

        mounted() {
            console.log("hello");
            this.orderarray();

        },
        methods: {

            orderarray(){
                var z=0

                for(var i=this.user;i<=this.length;i++){
                    Vue.set(this.sits, z, i);
                    z++;
                }
                for(var i=1;i<this.user;i++){
                    Vue.set(this.sits, z, i);
                    z++;
                }
                console.log(this.sits);

            }


        }
    }
</script>

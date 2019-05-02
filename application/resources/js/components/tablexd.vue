<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-2">{{sits[0]}}</div>
            <div class="col-2"></div>
            <div class="col-2"></div>
            <div class="col-2"></div>
            <div class="col-2"></div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "tablexd",
        data: function() {
            return {
                sits: [],
                user: 3,
                length: 6
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
                    this.sits[z]=i;
                    z++;
                }
                for(var i=0;i<this.user;i++){
                    this.sits[z]=i;
                    z++;
                }
                console.log(this.sits);

            }


        }
    }
</script>

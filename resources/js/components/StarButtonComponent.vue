<template>
    <button class="btn btn-info btn-md" @click.prevent="star">
        <i class="fas fa-star"></i>
        <span v-text="stars"></span>
    </button>
</template>

<script>
import axios from "axios";

export default {
    props: ["data-stars", "name"],
    data() {
        return {
            stars: this.dataStars
        };
    },
    methods: {
        star() {
            axios
                .post(`/api/users/${this.name}/stars`)
                .then(() => {
                    this.stars++;
                })
                .catch(({ response: { data } }) => {
                    flash(data, "danger");
                });
        }
    }
};
</script>

<template>
  <button type="submit" :class="classes" @click="toggle">
    <i class="fas fa-heart"></i>
    <span v-text="count"></span>
  </button>
</template>

<script>
export default {
  props: ["reply"],
  data() {
    return {
      count: this.reply.favoritesCount,
      isFavorited: this.reply.isFavorited,
    };
  },
  computed: {
    classes() {
      return ["btn", this.isFavorited ? "btn-primary" : "btn-secondary"];
    },
    endpoint() {
      return "/replies/" + this.reply.id + "/favorites";
    },
  },
  methods: {
    toggle() {
      this.isFavorited ? this.unfavorite() : this.favorite();
    },
    favorite() {
      axios.post(this.endpoint);
      this.count++;
      this.isFavorited = true;
    },
    unfavorite() {
      axios.delete(this.endpoint);
      this.count--;
      this.isFavorited = false;
    },
  },
};
</script>
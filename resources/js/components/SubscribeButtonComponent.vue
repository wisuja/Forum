<template>
  <button type="submit" :class="classes" @click="toggle">Subscribe</button>
</template>

<script>
export default {
  props: ["active"],
  computed: {
    classes() {
      return ["btn", this.active ? "btn-primary" : "btn-outline-primary"];
    },
  },

  methods: {
    toggle() {
      this.active ? this.unsubscribe() : this.subscribe();
    },
    subscribe() {
      axios.post(location.pathname + "/subscriptions").then(() => {
        flash("Subscribed!");

        this.active = true;
      });
    },
    unsubscribe() {
      axios.delete(location.pathname + "/subscriptions").then(() => {
        flash("Unsubscribed!");

        this.active = false;
      });
    },
  },
};
</script>
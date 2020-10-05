<template>
  <div>
    <div v-for="(reply, index) in items" v-bind:key="reply.id">
      <reply :data="reply" @deleted="remove(index)"></reply>
    </div>
  </div>
</template>
  
<script>
import Reply from "./ReplyComponent.vue";

export default {
  components: { Reply },
  props: ["data"],

  data() {
    return {
      items: this.data,
    };
  },

  methods: {
    remove(index) {
      this.items.splice(index, 1);

      this.$emit("removed");

      flash("Reply has been deleted.");
    },
  },
};
</script>
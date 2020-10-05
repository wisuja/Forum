<template>
  <div>
    <div v-for="(reply, index) in items" v-bind:key="reply.id">
      <reply :data="reply" @deleted="remove(index)"></reply>
    </div>
    <reply-form class="mt-3" :endpoint="endpoint" @created="add"></reply-form>
  </div>
</template>
  
<script>
import Reply from "./ReplyComponent.vue";
import ReplyForm from "./ReplyFormComponent.vue";

export default {
  components: { Reply, ReplyForm },
  props: ["data"],

  data() {
    return {
      items: this.data,
      endpoint: location.pathname + "/replies",
    };
  },

  methods: {
    add(reply) {
      this.items.push(reply);

      this.$emit("created");
    },
    remove(index) {
      this.items.splice(index, 1);

      this.$emit("removed");

      flash("Reply has been deleted.");
    },
  },
};
</script>
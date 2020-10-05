<template>
  <div :id="'reply_' + id" class="card mt-3">
    <div class="card-header">
      <div class="level">
        <h5 class="flex">
          <a :href="'/profile/' + data.owner.name" v-text="data.owner.name"></a>
          said
          <span v-text="ago"></span>
        </h5>

        <div v-if="checkSignIn">
          <favorite :reply="data"></favorite>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div v-if="editing">
        <div class="form-group">
          <textarea class="form-control" rows="5" v-model="body"></textarea>
        </div>

        <button class="btn btn-sm btn-primary" @click="update">Update</button>
        <button class="btn btn-sm btn-link" @click="editing = false">
          Cancel
        </button>
      </div>
      <div v-else v-text="body"></div>
    </div>
    <div class="card-footer" v-if="canUpdate">
      <div class="level">
        <button class="btn btn-warning btn-sm mr-3" @click="editing = true">
          Edit
        </button>
        <button class="btn btn-danger btn-sm mr-3" @click="destroy">
          Delete
        </button>
      </div>
    </div>
  </div>
</template>
<script>
import Favorite from "./FavoriteComponent.vue";
import moment from "moment";

export default {
  props: ["data"],
  components: { Favorite },
  computed: {
    checkSignIn() {
      return window.App.signedIn;
    },
    canUpdate() {
      return this.authorize((user) => this.data.user_id == user.id);
    },
    ago() {
      return moment(this.data.created_at).fromNow();
    },
  },
  data() {
    return {
      editing: false,
      body: this.data.body,
      id: this.data.id,
    };
  },
  methods: {
    update() {
      axios.patch("/replies/" + this.id, {
        body: this.body,
      });

      this.editing = false;

      flash("Reply has been updated.");
    },
    destroy() {
      axios.delete("/replies/" + this.id);

      this.$emit("deleted", this.id);
    },
  },
};
</script>
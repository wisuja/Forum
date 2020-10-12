<template>
  <div>
    <div class="level">
      <img :src="avatar" width="50" height="50" class="mr-1">
      <h6 class="flex">
        <h1 v-text="user.name"></h1>
        <small v-text="joinedSince"></small>
      </h6>
    </div>
    <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <image-upload name="avatar" @loaded="onLoad" class="form-control-file"></image-upload>
      </div>
    </form>
  </div>
</template>

<script>
import moment from 'moment';
import ImageUpload from './ImageUploadComponent.vue';
export default {
  props: ['user'],
  components: { ImageUpload },
  data() {
    return {
      avatar:  this.user.avatar_path
    }
  },
  computed: {
    canUpdate() {
      return this.authorize(user => user.id == this.user.id);
    },
    joinedSince () {
      return `Joined since ${moment(this.user.created_at).fromNow()}`;
    }
  },
  methods: {
    onLoad(avatar) {
      this.avatar = avatar.src;

      this.persist(avatar.file)
    },

    persist(avatar) {
      let data = new FormData();
      data.append('avatar', avatar);
      
      axios.post(`/api/users/${this.user.name}/avatar`, data)
        .then(flash('Avatar uploaded'));
    }
  }
}
</script>
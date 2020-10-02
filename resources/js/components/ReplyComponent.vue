<script>
import Favorite from "./FavoriteComponent.vue";

export default {
  props: ["attributes"],
  components: { Favorite },
  data() {
    return {
      editing: false,
      body: this.attributes.body,
    };
  },
  methods: {
    update() {
      axios.patch("/replies/" + this.attributes.id, {
        body: this.body,
      });

      this.editing = false;

      flash("Reply has been updated.");
    },
    destroy() {
      axios.delete("/replies/" + this.attributes.id);

      $(this.$el).fadeOut(300, () => {
        flash("Reply has been deleted.");
      });
    },
  },
};
</script>
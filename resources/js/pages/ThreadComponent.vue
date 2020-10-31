<script>
import Replies from "../components/RepliesComponent.vue";
import SubscribeButton from "../components/SubscribeButtonComponent.vue";

export default {
    props: ["thread"],
    components: { Replies, SubscribeButton },
    data() {
        return {
            repliesCount: this.thread.replies_count,
            locked: this.thread.locked,
            editing: false,
            form: {
                title: this.thread.title,
                body: this.thread.body
            }
        };
    },
    methods: {
        toggleLock() {
            let uri = `/locked-threads/${this.thread.slug}`;
            axios[this.locked ? "delete" : "post"](uri);
            this.locked = !this.locked;
        },
        update() {
            let uri = `/threads/${this.thread.channel.slug}/${this.thread.slug}`;
            axios
                .patch(uri, this.form)
                .then(() => {
                    this.editing = false;
                    flash("Your thread has been updated.");
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        flash(
                            "We encountered a problem when processing your requests. Please try again later.",
                            "danger"
                        );
                    }
                });
        },
        cancel() {
            this.editing = false;

            this.form = {
                title: this.thread.title,
                body: this.thread.body
            };
        },
        updateFormPanel(item) {
            this.repliesCount++;
            this.locked = item.isThreadLocked;
        }
    }
};
</script>

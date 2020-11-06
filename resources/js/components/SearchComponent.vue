<template>
    <div class="container">
        <ais-instant-search
            :search-client="searchClient"
            index-name="threads"
            query="abc"
        >
            <div class="row">
                <div class="col-md-8">
                    <ais-infinite-hits>
                        <template slot="item" slot-scope="{ item }">
                            <h3>
                                <a :href="item.path">
                                    <ais-highlight
                                        :hit="item"
                                        attribute="title"
                                    />
                                </a>
                            </h3>
                            <p>
                                Posted by:
                                <a :href="'/profiles/' + item.creator.name">{{
                                    item.creator.name
                                }}</a>
                            </p>
                        </template>
                    </ais-infinite-hits>
                </div>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            Search
                        </div>
                        <div class="card-body">
                            <ais-search-box
                                placeholder="Search threads..."
                                autofocus
                                v-model="query"
                            ></ais-search-box>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            Channels
                        </div>
                        <div class="card-body">
                            <ais-refinement-list
                                attribute="channel.name"
                                show-more
                                :show-more-limit="50"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </ais-instant-search>
    </div>
</template>

<script>
import algoliasearch from "algoliasearch/lite";

export default {
    props: ["data-query"],
    data() {
        return {
            searchClient: algoliasearch(
                process.env.MIX_ALGOLIA_APP_ID,
                process.env.MIX_ALGOLIA_SECRET
            ),
            query: this.dataQuery
        };
    }
};
</script>

<style>
.ais-InfiniteHits-list {
    display: block;
    width: 100%;
}

.ais-InfiniteHits-item {
    width: 100%;
    overflow: hidden;
}

.ais-Highlight {
    word-wrap: break-word;
}
</style>

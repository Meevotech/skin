let mix = require("laravel-mix");

const tailwindcss = require("tailwindcss");

mix.options({
    processCssUrls: false,
    autoprefixer: {
        enabled: true
    }
})
    .setPublicPath("/")
    .less("res/assets/less/october.less", "res/assets/css/october.css")
    .less(
        "res/assets/less/system/assets/ui/storm.less",
        "res/assets/less/system/assets/ui/storm.css"
    )
    .options({
        postCss: [tailwindcss("./tailwind.config.js")]
    })
    .version();

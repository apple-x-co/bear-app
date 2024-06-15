import terser from '@rollup/plugin-terser';

export default {
    input: 'main.js',
    output: [
        {
            file: '../admin/js/index.js',
            format: 'esm',
        },
        {
            file: '../admin/js/index.min.js',
            format: 'esm',
            plugins: [terser()],
        }
    ],
};

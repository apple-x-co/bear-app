import terser from '@rollup/plugin-terser';

export default {
    input: 'main.js',
    output: [
        {
            file: 'output/index.js',
            format: 'esm',
        },
        {
            file: 'output/index.min.js',
            format: 'esm',
            plugins: [terser()],
        }
    ],
};

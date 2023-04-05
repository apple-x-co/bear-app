import { bundle } from 'https://deno.land/x/emit/mod.ts';
const result = await bundle(
    new URL(import.meta.resolve('./main.ts')),
);

const { code } = result;
Deno.writeTextFile('../../admin/js/index.js', code);

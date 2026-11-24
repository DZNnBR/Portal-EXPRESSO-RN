import { existsSync, readFileSync } from 'node:fs';
import { join } from 'node:path';

const root = process.cwd();
const required = [
  'site/wp-content/mu-plugins/edicao/materias.json',
  'site/wp-content/plugins/elementor/elementor.php',
  'site/wp-content/themes/diario-do-trairi/style.css',
  'site/wp-content/themes/diario-do-trairi/front-page.php',
  'site/wp-content/mu-plugins/diario-trairi-setup.php',
  'blueprint.json'
];

const missing = required.filter((file) => !existsSync(join(root, file)));
if (missing.length) {
  console.error('Arquivos ausentes:\n' + missing.join('\n'));
  process.exit(1);
}

const blueprint = JSON.parse(readFileSync(join(root, 'blueprint.json'), 'utf8'));
if (!Array.isArray(blueprint.steps) || blueprint.steps.length < 3) {
  console.error('Blueprint incompleto.');
  process.exit(1);
}

const edicao = 'site/wp-content/mu-plugins/edicao';
const materias = JSON.parse(readFileSync(join(root, edicao, 'materias.json'), 'utf8'));
if (!materias.length || materias.some(item => !item.credit || !existsSync(join(root, edicao, item.file)))) {
  throw new Error('A edição precisa de fotos e créditos para todas as matérias.');
}
console.log('Estrutura do Portal Expresso RN validada com sucesso.');

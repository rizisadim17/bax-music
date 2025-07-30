import { Application } from '@hotwired/stimulus';

// Import controllers manually
import CharacterToggleDetailsController from './character-toggle-details-controller';
import EpisodesController from './episodes_controller';
import MenuController from './menu_controller';

const application = Application.start();

// Register controllers manually
application.register('character-toggle', CharacterToggleDetailsController);
application.register('episodes-toggle', EpisodesController);
application.register('menu', MenuController);

export { application };
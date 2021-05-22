import { moduleMetadata } from '@storybook/angular';
import { CommonModule } from '@angular/common';
// @ts-ignore
import { Story, Meta } from '@storybook/angular/types-6-0';
import Page from './page.component';

import * as HeaderStories from './Header.stories';
import {ButtonComponent} from '../app/shared/ui/component/button/button.component';
import { YesNavComponent } from '../app/navigation/ui/yes-nav/yes-nav.component';

export default {
  title: 'Example/Page',
  component: YesNavComponent,
  decorators: [
    moduleMetadata({
      declarations: [ButtonComponent, YesNavComponent],
      imports: [CommonModule],
    }),
  ],
} as Meta;

const Template: Story<Page> = (args: Page) => ({
  component: Page,
  props: args,
});

export const LoggedIn = Template.bind({});
LoggedIn.args = {
  ...HeaderStories.LoggedIn.args,
};

export const LoggedOut = Template.bind({});
LoggedOut.args = {
  ...HeaderStories.LoggedOut.args,
};

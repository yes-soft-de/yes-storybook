import { moduleMetadata } from '@storybook/angular';
import { CommonModule } from '@angular/common';
// @ts-ignore
import { Story, Meta } from '@storybook/angular/types-6-0';

import Header from './header.component';
import {ButtonComponent} from '../app/shared/ui/component/button/button.component';

export default {
  title: 'Example/Header',
  component: Header,
  decorators: [
    moduleMetadata({
      declarations: [ButtonComponent],
      imports: [CommonModule],
    }),
  ],
} as Meta;

const Template: Story<Header> = (args: Header) => ({
  component: Header,
  props: args,
});

export const LoggedIn = Template.bind({});
LoggedIn.args = {
  user: {},
};

export const LoggedOut = Template.bind({});
LoggedOut.args = {};

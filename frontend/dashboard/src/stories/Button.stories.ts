// also exported from '@storybook/angular' if you can deal with breaking changes in 6.1

// @ts-ignore
import {Story, Meta} from '@storybook/angular/types-6-0';

import {ButtonComponent} from '../app/shared/ui/component/button/button.component';

export default {
  title: 'Shared/Button',
  component: ButtonComponent,
  argTypes: {
    color: {control: 'color'}
  },
} as Meta;

const Template: Story<ButtonComponent> = (args: ButtonComponent) => ({
  component: ButtonComponent,
  props: args,
});

export const Flat = Template.bind({});
Flat.args = {
  ...Template.args,
  color: Flat.args?.color ?? 'blue',
  label: 'Flat Button',
  size: Flat.args?.size ?? 'small',
  type: 'flat',
};

export const Outline = Template.bind({});
Outline.args = {
  ...Template.args,
  color: Outline.args?.color ?? 'blue',
  size: Outline.args?.size ?? 'small',
  type: 'outline',
  label: 'Outline Button',
};

export const Text = Template.bind({});
Text.args = {
  ...Template.args,
  color: Text.args?.color ?? 'blue',
  size: Text.args?.size ?? 'small',
  type: 'text',
  label: 'Text Button',
};
